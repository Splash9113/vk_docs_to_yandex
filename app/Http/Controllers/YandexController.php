<?php

namespace App\Http\Controllers;

use App\Jobs\SendEmail;
use App\UploadFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class YandexController extends Controller
{
    public function addToDownload(Request $request)
    {
        $disk = new \Arhitector\Yandex\Disk(session()->get('yandex_token'));
        file_put_contents('tmp/' . $request->title, fopen($request->link, 'r'));
        $resource = $disk->getResource($request->title);
        $resource->upload('tmp/' . $request->title);
        unlink('tmp/' . $request->title);


        $fields = array(
            'title' => $request->title,
            'doc_id' => $request->doc_id,
            'email' => Auth::user()->email,
            'link' => $request->link
        );
        $file = new UploadFile($fields);
        $file->save();
        dispatch(new SendEmail($file));
    }
}
