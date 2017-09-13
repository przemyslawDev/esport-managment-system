@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="panel panel-default">
            <div class="panel-body">
                <employee id="{{ $id }}"></employee>
            </div>
        </div>
    </div>
@endsection