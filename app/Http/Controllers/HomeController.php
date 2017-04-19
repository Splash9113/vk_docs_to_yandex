<?php

namespace App\Http\Controllers;

use App\UploadFile;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!session()->get('vk_token')) {
            $data = [
                'client_id' => env('VK_APP_ID'),
                'display' => 'page',
                'url' => url()->current() . '/docs',
                'scope' => 'docs',
                'response_type' => 'code',
                'v' => '5.63'
            ];
            return view('home', $data);
        } else {
            return redirect()->route('docList');
        }
    }

    public function showDocs(Request $request)
    {
        if ($request->code) {
            $this->checkVkToken($request);
            $this->checkYandexToken($request);
        }
        $docs = $this->getDocsFromVk();
        $uploadedDocs = UploadFile::all();
        foreach ($docs as $key => $doc) {
            foreach ($uploadedDocs as $uploadedDoc) {
                if ($doc['id'] == $uploadedDoc->doc_id) {
                    $docs[$key]['isUpload'] = true;
                }
            }
            $docs[$key]['isUpload'] = isset($docs[$key]['isUpload']) ?? false;
        }
        return view('docsList', ['docs' => $docs]);
    }

    private function checkYandexToken($request)
    {
        if (!session()->get('yandex_token')) {
            $url = 'https://oauth.yandex.ru/token';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=authorization_code&client_id=" . env('YANDEX_APP_ID') . "&client_secret=" . env('YANDEX_APP_SECRET') . "&code=$request->code");
            $result = json_decode(curl_exec($ch), true);
            curl_close($ch);

            isset($result['access_token']) ? session()->put('yandex_token', $result['access_token']) : '';
        }
    }

    private function checkVkToken($request)
    {
        if (!session()->get('vk_token')) {
            $url = 'https://oauth.vk.com/access_token';
            $url .= '?client_id=' . env('VK_APP_ID');
            $url .= '&client_secret=' . env('VK_APP_SECRET');
            $url .= '&redirect_uri=' . url()->current();
            $url .= '&code=' . $request->code;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $result = json_decode(curl_exec($ch), true);
            curl_close($ch);

            isset($result['access_token']) ? session()->put('vk_token', $result['access_token']) : '';
        }
    }

    private function getDocsFromVk()
    {
        $url = 'https://api.vk.com/method/';
        $url .= 'docs.get';
        $url .= '?access_token=' . session()->get('vk_token');
        $url .= '&v=5.63';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = json_decode(curl_exec($ch), true);
        curl_close($ch);

        return $result['response']['items'];
    }
}
