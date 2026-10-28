<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>သောင်းပြောင်းထွေရာ</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Myanmar:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="guest-body">
    <main class="auth-page">
        <section class="auth-container">
            <header class="auth-header">
                <a href="{{ route('feed.index') }}" class="auth-brand" aria-label="သောင်းပြောင်းထွေရာ ပင်မစာမျက်နှာ">
                    <img src="{{ asset('images/logo.jpg') }}" alt="သောင်းပြောင်းထွေရာ" class="auth-logo">
                </a>
                <h1>သောင်းပြောင်းထွေရာ</h1>
                <p>စိတ်ဝင်စားရာတွေကို မျှဝေပြီး မိတ်ဆွေသစ်တွေနဲ့ ချိတ်ဆက်လိုက်ပါ။</p>
            </header>
            <div class="auth-form-container">{{ $slot }}</div>
        </section>
        <footer class="auth-footer">© {{ date('Y') }} သောင်းပြောင်းထွေရာ</footer>
    </main>
</body>

</html>
