<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title', config('app.name', 'FoodLab Academy'))</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>[x-cloak]{display:none!important}</style>
    </head>
    <body class="font-sans antialiased bg-stone-50 text-slate-800">
        <div class="min-h-screen">
            @include('layouts.navigation')
            @isset($header)
                <header class="bg-white border-b border-stone-200">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset
            @if (session('status'))
                <div class="max-w-7xl mx-auto px-4 pt-4">
                    <div class="rounded-lg bg-emerald-50 text-emerald-800 px-4 py-3 text-sm">{{ session('status') }}</div>
                </div>
            @endif
            @if (session('error'))
                <div class="max-w-7xl mx-auto px-4 pt-4">
                    <div class="rounded-lg bg-red-50 text-red-800 px-4 py-3 text-sm">{{ session('error') }}</div>
                </div>
            @endif
            <main>
                {{ $slot }}
            </main>
            @include('layouts.footer')
        </div>
        <x-cookie-banner />
    </body>
</html>
