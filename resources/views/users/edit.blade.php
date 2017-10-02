@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">{{ _('Edit user') }}</h1>
            </div>
        </div>
        <user-edit id="{{ $id }}"></user-edit>
    </div>
@endsection