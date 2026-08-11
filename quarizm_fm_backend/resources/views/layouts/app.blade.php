<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>لوحة تحكم المدير - كوارزم</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container">
            <span class="navbar-brand">كوارزم - لوحة تحكم المدير</span>
            @auth
            <form action="{{ route('dashboard.logout') }}" method="POST" class="d-flex">
                @csrf
                <span class="text-white me-3 align-self-center">{{ auth()->user()->name }}</span>
                <button class="btn btn-outline-light btn-sm">تسجيل الخروج</button>
            </form>
            @endauth
        </div>
    </nav>
    <div class="container pb-5">
        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif
        @yield('content')
    </div>
</body>
</html>
