<!DOCTYPE html>
<html lang="ar" dir="rtl">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>@yield('title', 'نظام إدارة الموظفين')</title>
        <link rel="preload" href="https://fonts.googleapis.com/icon?family=Material+Icons" as="style" onload="this.onload=null;this.rel='stylesheet'">
        {{-- <link rel="stylesheet" href="https://cdn.tailwindcss.com"> --}}
        <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    @auth
        @include('layouts.partials.navbar')
        <div class="flex">
            @include('layouts.partials.sidebar')
            <main class="flex-1 p-6">
                @yield('content')
            </main>
        </div>
        @include('layouts.partials.footer')
    @else
        <div class="flex justify-center items-center h-screen">
            @yield('content')
        </div>
    @endauth
</body>
</html>
