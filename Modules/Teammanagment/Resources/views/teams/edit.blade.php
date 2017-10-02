@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">{{ _('Edit team') }}</h1>
            </div>
        </div>
        <team-edit id="{{ $id }}"></team-edit>
    </div>
@endsection