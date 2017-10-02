@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">{{ _('Teams') }}</h1>
            </div>
        </div>
        <teams ismanager="{{ Auth::user()->hasRole('manager') }}"
               canedit="{{ Auth::user()->hasRole(['system_admin'])}}"></teams>
    </div>
@endsection