@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">{{ _('Users') }}</h3>
            </div>
            <div class="panel-body">
                 <users></users>
            </div>
        </div>
    </div>
@endsection