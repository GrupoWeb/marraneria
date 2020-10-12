<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Marranex</title>

    <!-- Scripts -->
    <!-- <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/all.js') }}"></script> -->

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/all.css') }}">

    <!-- Bootstrap core CSS -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- llamado a bootstrap -->
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    <!-- <script src="{{ asset('js/dashboard.js')}}"></script> -->

    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }
        
        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }
    </style>
    <!-- Custom styles for this template -->
    <!-- <link href="dashboard.css" rel="stylesheet"> -->
</head>
<!-- <body class="app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show"> -->
    <!-- <body class="app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show"> -->
    <body>
        
    @include('common.header')
    <div>
        
        <div >
            @include('common.sidebar')
            <!-- <button class="sidebar-minimizer brand-minimizer" type="button"></button> -->
        </div>    
    
    <!-- </main> -->
        <main class="col-md-9 ml-sm-auto col-lg-10 px-md-4" id='app'>
            <!-- aqui va todo el codigo de vistar -->
           <!-- <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom"> -->
           <div class="pt-5">
             @yield('content')                   
            </div>
        </main>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src=" {{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    

</body>
</html>
