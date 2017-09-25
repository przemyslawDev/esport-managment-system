@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">{{ _('Teams') }}</h3>
            </div>
            <div class="panel-body">
                <teams canedit="{{ Auth::user()->hasRole(['system_admin'])}}"></teams>
            </div>
        </div>
    </div>
@endsection