@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">{{ _('Dashboard') }}</h3>
            </div>
            <div class="panel-body">
                <dashboard></dashboard>
            </div>
        </div>
    </div>
@endsection
