<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Admin</title>
</head>
<body>
    <h2>Login Admin</h2>

    @if($errors->any())
        <div style="color:red;">
            <ul>
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ url('/login') }}" method="POST">
        @csrf
        <label>Email</label><br>
        <input type="email" name="email" required><br>

        <label>Password</label><br>
        <input type="password" name="password" required><br>

        <button type="submit">Login</button>
    </form>
</body>
</html>
