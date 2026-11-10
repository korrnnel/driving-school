<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Szkoła jazdy') }}</title>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        .sidebar { min-height: calc(100vh - 56px); }
    </style>
</head>
<body class="bg-light">
    @include('layouts.navigation')

    <div class="container-fluid">
        <div class="row">
            @if(Auth::check() && Auth::user()->role === 'admin')
                @include('layouts.sidebar')
            @endif

            <main class="{{ (Auth::check() && Auth::user()->role === 'admin') ? 'col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4' : 'col-12 px-md-4 py-4' }}">
                @isset($header)
                    <div class="pb-3 mb-4 border-bottom">
                        {{ $header }}
                    </div>
                @endisset

                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>
