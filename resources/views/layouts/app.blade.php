<!DOCTYPE html>
<html>
<head>
    <title>@yield('title', 'Admin Panel')</title>
</head>
<body>
    <header>
        <h1>Admin Panel</h1>
        @auth
        <nav>
            <a href="/dashboard">Dashboard</a> |
            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit">Logout</button>
            </form>
        </nav>
        @endauth
        <hr>
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        <hr>
        <p>&copy; 2025 Admin Only Website</p>
    </footer>
</body>
</html>
