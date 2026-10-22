<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin | Coffee Break</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body class="admin-body">
    <main class="login-card">
        <a class="admin-brand" href="{{ route('home') }}">Coffee <span>Break.</span></a>
        <h1>Login Admin</h1>
        <p>Masuk untuk mengelola menu Coffee Break.</p>

        @if ($errors->any())
            <div class="alert alert-error" role="alert">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.store') }}" class="admin-form">
            @csrf

            <label for="email">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username">

            <label for="password">Password</label>
            <input id="password" name="password" type="password" required autocomplete="current-password">

            <label class="checkbox-label">
                <input name="remember" type="checkbox" value="1">
                Ingat saya
            </label>

            <button class="button button-primary" type="submit">Masuk</button>
        </form>

        <a class="back-link" href="{{ route('home') }}">← Kembali ke website</a>
    </main>
</body>
</html>
