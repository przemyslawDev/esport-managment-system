@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">{{ _('User') }}</h3>
            </div>
            <div class="panel-body">
                <user id="{{ $id }}"></user>
            </div>
        </div>
    </div>
@endsection