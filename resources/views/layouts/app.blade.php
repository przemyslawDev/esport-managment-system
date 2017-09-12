<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    @if(Auth::check())
        @include('layouts.nav')
    <div id="app">
        <div class="container-fluid">
            <div class="row">
                 <div class="col-sm-3 col-lg-2">
                    @include('layouts.side-nav')
                 </div>
                 <div class="col-sm-9 col-lg-10">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    @else
         <div id="app">
             @yield('content')
         </div>
    @endif

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
