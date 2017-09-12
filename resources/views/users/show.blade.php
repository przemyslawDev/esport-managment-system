@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="panel panel-default">
            <div class="panel-body">
                <user id="{{ $id }}"></user>
            </div>
        </div>
    </div>
@endsection