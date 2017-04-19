@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Dashboard</div>
                    <div class="panel-body">
                        <a href="https://oauth.vk.com/authorize?client_id={{ $client_id }}&display={{ $display }}&redirect_uri={{ $url }}&scope={{ $scope }}&response_type={{ $response_type }}&v={{ $v }}">
                            Login in VK.com
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
