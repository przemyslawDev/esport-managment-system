@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">{{ _('Edit User') }}</h3>
            </div>
            <div class="panel-body">
                <user-edit id="{{ $id }}"></user-edit>
            </div>
        </div>
    </div>
@endsection