@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">{{ _('Edit Team') }}</h3>
            </div>
            <div class="panel-body">
               <team-edit id="{{ $id }}"></team-edit>
            </div>
        </div>
    </div>
@endsection