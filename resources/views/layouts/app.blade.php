<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $title ?? 'Registro y certificación de perros de raza' }}</title>
        <!-- Favicon -->
        <link rel="shortcut icon" sizes="192x192" type="image/x-icon" href="{{ asset('assets/img/icons/favicon.ico') }}">
        <!-- Apple Touch Icon -->
        <link rel="apple-touch-icon" sizes="192x192" href="{{ asset('assets/img/icons/favicon.ico') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <link href="https://cdnjs.cloudflare.com/ajax/libs/intro.js/7.2.0/introjs.min.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/intro.js/7.2.0/intro.min.js"></script>
        
        <!-- Scripts -->
        <script src="{{ asset('assets/js/crypto-js.min.js') }}"></script>
        <script src="{{ asset('assets/js/sweetalert2.all.min.js') }}"></script>
        @vite(['resources/sass/app.scss', 'resources/js/app.js'])
       
    </head>
    <body>

    <div class="container-dashboard">
        <!-- ======== Side============ -->
        @include('layouts.aside')

        <div class="main">

            <!-- ======== Nav ============ -->
            @include('layouts.navigation')
            
            <div class="content">
                <!-- ======== Content ============ -->
                <div class="box-content ">
                    {{ $slot }}
                </div>

            </div>
        </div>
    </div>


    </body>
</html>
