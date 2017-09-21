@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">{{ _('Create Team') }}</h3>
            </div>
            <div class="panel-body">
                <team-create></team-create>
            </div>
        </div>
    </div>
@endsection