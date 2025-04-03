<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Register Hire</title>
        <!-- Favicon -->
        <link rel="shortcut icon" sizes="192x192" type="image/x-icon" href="{{ asset('assets/img/icons/favicon.ico') }}">
        <!-- Apple Touch Icon -->
        <link rel="apple-touch-icon" sizes="192x192" href="{{ asset('assets/img/icons/favicon.ico') }}">
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        <!-- Scripts -->
        @vite(['resources/css/app.css'])
    </head>
    <body class="font-sans text-gray-900 antialiased">

        <div>

            {{ $slot }}
        </div>
        
    </body>
</html>