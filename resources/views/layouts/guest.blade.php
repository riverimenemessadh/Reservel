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

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            transition: direction 0.3s ease;
        }
    </style>
</head>

<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div class="flex flex-col items-center">
            <div class="mb-4 flex gap-1 p-1 border rounded-lg bg-gray-50" style="width: fit-content;">
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

            <a href="/">
                <x-application-logo />
            </a>
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            {{ $slot }}
        </div>
    </div>
    
    <script>
        // Ensure locale is properly set and page direction updates
        document.documentElement.lang = '{{ app()->getLocale() }}';
        document.documentElement.dir = '{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}';
    </script>
</body>

</html>
