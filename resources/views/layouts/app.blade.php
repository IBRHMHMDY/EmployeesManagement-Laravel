<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'نظام إدارة الموظفين')</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://cdn.tailwindcss.com">
</head>
<body class="bg-gray-100">
    @include('layouts.navbar')
    <div class="flex">
        @include('layouts.sidebar')
        <main class="flex-1 p-6">
            @yield('content')
        </main>
    </div>
    @include('layouts.footer')
    <script src="https://cdn.tailwindcss.com"></script>
</body>
</html>
