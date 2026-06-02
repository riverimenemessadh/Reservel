<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Reservel') }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('Reservel-favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            transition: direction 0.3s ease;
        }
    </style>
</head>

<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col md:flex-row">

        {{-- Left Panel: hidden on mobile --}}
        <div class="hidden md:flex md:w-1/2 flex-col items-center justify-center gap-5" style="background-color: #154269;">
            <div class="hidden md:flex md:w-1/2 flex-col items-center justify-center gap-5" style="background-color: #154269;">
    <img src="{{ asset('login-panel.png') }}" style="height: 380px; width: auto;" alt="Reservel">
</div>
        </div>

        {{-- Right Panel --}}
        <div class="w-full md:w-1/2 flex flex-col items-center justify-center min-h-screen bg-white px-6 py-10">

            {{-- Language Switcher --}}
            <div class="mb-6 flex gap-1 p-1 border rounded-lg bg-gray-50" style="width: fit-content;">
                <a href="{{ url('lang/fr') }}"
                    class="px-4 py-2 text-sm rounded-md transition-all duration-200 hover:scale-105 flex items-center justify-center {{ app()->getLocale() == 'fr' ? 'font-bold shadow-md' : 'text-gray-500 hover:text-gray-700' }}"
                    style="{{ app()->getLocale() == 'fr' ? 'background-color: #154269; color: #ffffff;' : '' }}">
                    Français
                </a>
                <a href="{{ url('lang/ar') }}"
                    class="px-4 py-2 text-sm rounded-md transition-all duration-200 hover:scale-105 flex items-center justify-center {{ app()->getLocale() == 'ar' ? 'font-bold shadow-md' : 'text-gray-500 hover:text-gray-700' }}"
                    style="{{ app()->getLocale() == 'ar' ? 'background-color: #154269; color: #ffffff;' : '' }}">
                    العربية
                </a>
            </div>

            {{-- Logo visible on mobile only --}}
            <div class="mb-4 md:hidden">
                <a href="/">
                    <x-application-logo style="height: 350px; width: auto; margin-top: 40px; margin-bottom: 10px;" />
                </a>
            </div>

            {{-- Auth Form Slot --}}
            <div class="w-full sm:max-w-md px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </div>

    {{-- Bootstrap JS bundle for modal behavior --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.documentElement.lang = '{{ app()->getLocale() }}';
        document.documentElement.dir = '{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}';
    </script>

</body>

</html>