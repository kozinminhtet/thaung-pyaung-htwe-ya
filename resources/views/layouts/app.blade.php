<!DOCTYPE html>
<html lang="my">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>သောင်းပြောင်းထွေရာ</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Myanmar:wght@400;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

</head>

<body>

    @include('partials.navbar')

    <div class="container mt-3">

        <div class="row">

            @include('partials.sidebar-left')

            <div class="col-lg-6 col-md-12 main-feed">

                @yield('content')

            </div>

            @include('partials.sidebar-right')

        </div>

    </div>

    @include('partials.bottom-nav')

    <script src="{{ asset('js/app.js') }}"></script>


</body>

</html>