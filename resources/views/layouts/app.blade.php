<!DOCTYPE html>
<html>
<head>
    <title>@yield('title', 'Admin Panel')</title>
    @vite('resources/css/app.css')

</head>
<body class="bg-gray-100 flex min-h-screen">

    <!-- Sidebar dipanggil dari component -->
    @include('components.sidebar')

    <!-- Konten Utama -->
    <main class="flex-1 p-6">
        <!-- Header Konten Utama Sederhana -->
        <div class="bg-white rounded-lg shadow-sm p-5 mb-6">
            <h1 class="text-2xl font-semibold text-gray-800 mb-1">@yield('page_title', 'Selamat Datang')</h1>
            <p class="text-gray-600">@yield('page_description', 'Ini adalah halaman administrasi Anda.')</p>
        </div>

        <!-- Slot untuk konten spesifik halaman -->
        @yield('content')
    </main>

</body>
</html>
