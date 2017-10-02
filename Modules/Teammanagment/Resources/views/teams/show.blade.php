@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <team id="{{ $id }}" canedit="{{ Auth::user()->hasRole(['system_admin'])}}"></team>
    </div>
@endsection