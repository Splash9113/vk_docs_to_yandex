@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Docs
                        <span class="right-header">
                            @if (!session()->get('yandex_token'))
                                <a href="https://oauth.yandex.ru/authorize?response_type=code&client_id={{ env('YANDEX_APP_ID') }}">
                                Connect to Yandex for upload
                            </a>
                            @else
                                <button class="btn btn-info upload-checked">Upload checked</button>
                            @endif
                        </span>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-1">
                                <input type="checkbox" class="check-all">
                            </div>
                            <div class="col-md-6">
                                <b>File Name</b>
                            </div>
                            <div class="col-md-3">
                                <b>Size</b>
                            </div>
                        </div>
                        @foreach ($docs as $doc)
                            <div class="row js-file-to-upload"
                                 data-link="{{ $doc['url'] }}"
                                 data-url="{{route('addToDownload')}}"
                                 data-title="{{ $doc['title'] }}"
                                 data-id="{{ $doc['id'] }}">
                                <div class="col-md-1">
                                    <input type="checkbox" class="check-doc">
                                </div>
                                <div class="col-md-6">
                                    {{ $doc['title'] }}
                                </div>
                                <div class="col-md-3">
                                    {{ $doc['size'] }}
                                </div>
                                <div class="col-md-2">
                                    @if (session()->get('yandex_token'))
                                        @if ($doc['isUpload'])
                                            <a class="done" href="#">Done</a>
                                        @else
                                            <a class="jquery-postback" href="#">Upload</a>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
