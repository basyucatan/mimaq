<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="{{ asset('img/logo.ico') }}" type="image/x-icon">
    <title>{{ config('app.name', 'Laravel') }} | @hasSection('title')
            @yield('title')
        @endif
    </title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @vite(['resources/js/app.js'])
    @livewireStyles
    <link href="{{ asset('css/cssBase.css') }}" rel="stylesheet">
    @stack('styles')
</head>

<body>
    @include('layouts.sidebar')
    <main style="width: 100%; overflow-y: auto; padding-top: 60px;">
        @yield('content')
    </main>
    @livewireScripts    
    @stack('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/jsBase.js') }}"></script>
</body>
</html>
