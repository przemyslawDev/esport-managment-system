@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">{{ _('Dashboard') }}</h1>
            </div>
        </div>
        <dashboard></dashboard>
    </div>
@endsection
