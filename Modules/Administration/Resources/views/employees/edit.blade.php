@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">{{ _('Edit employee') }}</h1>
            </div>
        </div>
        <employee-edit id="{{ $id }}"></employee-edit>
    </div>
@endsection