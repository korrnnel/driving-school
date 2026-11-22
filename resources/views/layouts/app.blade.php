<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'OSK L-auto') }}</title>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body class="d-flex flex-column min-vh-100 bg-light">
    @include('layouts.navigation', ['withSidebar' => true])

    <div class="container-fluid flex-grow-1 d-flex flex-column">
        <div class="row flex-grow-1">
            @if(Auth::check())
                @include('layouts.sidebar')
            @endif

            <main class="{{ Auth::check() ? 'col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4' : 'col-12 px-md-4 py-4' }}">
                @isset($header)
                    <div class="pb-3 mb-4 border-bottom">
                        {{ $header }}
                    </div>
                @endisset

                {{ $slot }}
            </main>
        </div>
    </div>

    @include('layouts.footer')
</body>
</html>
