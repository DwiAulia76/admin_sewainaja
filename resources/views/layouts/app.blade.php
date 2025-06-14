<!DOCTYPE html>
<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel')</title>
    @vite('resources/css/app.css')

</head>
<body class="bg-gray-100 flex min-h-screen">

    <!-- Sidebar dipanggil dari component -->
    @include('components.sidebar')

    <!-- Konten Utama -->
    <main class="flex-1 p-6">
        <!-- Header Konten Utama Sederhana -->
       

        <!-- Slot untuk konten spesifik halaman -->
        @yield('content')
    </main>

</body>
</html>
