@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">{{ _('Edit Employee') }}</h3>
            </div>
            <div class="panel-body">
                <employee-edit id="{{ $id }}"></employee-edit>
            </div>
        </div>
    </div>
@endsection