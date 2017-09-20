@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="panel panel-default">
            <div class="panel-body">
                <game id="{{ $id }}"></game>
            </div>
        </div>
    </div>
@endsection