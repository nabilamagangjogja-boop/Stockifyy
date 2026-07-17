<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $appSetting->app_name ?? 'Stockify' }}</title>
    <link rel="icon" type="image/png"
        href="{{ ($appSetting && $appSetting->logo_path) ? asset('storage/' . $appSetting->logo_path) : asset('images/logo.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gradient-to-br from-white via-gray-100 to-gray-300 text-ink">
    <main class="relative w-full h-screen overflow-hidden flex items-center justify-center">
        @yield('content')
    </main>
</body>

</html>