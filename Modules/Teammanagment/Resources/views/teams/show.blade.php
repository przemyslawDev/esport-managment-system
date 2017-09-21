@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="panel panel-default">
            <div class="panel-body">
               <team id="{{ $id }}"></team>
            </div>
        </div>
    </div>
@endsection