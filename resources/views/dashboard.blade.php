<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
</head>
<body>
    <h1>Selamat Datang, {{ auth()->user()->name }}</h1>
    <p>Role: {{ auth()->user()->role }}</p>

    <form action="{{ url('/logout') }}" method="POST">
        @csrf
        <button type="submit">Logout</button>
    </form>
</body>
</html>
