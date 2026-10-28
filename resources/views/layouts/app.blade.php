<!DOCTYPE html>
<html lang="my">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>သောင်းပြောင်းထွေရာ</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Myanmar:wght@400;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @stack('styles')

</head>

<body>

    @include('partials.navbar')

    @if (request()->is('admin*'))
        <main class="container-fluid px-3 px-lg-4 mt-3">
            @yield('content')
        </main>
    @else
        <div class="container mt-3">

            <div class="row">

                @include('partials.sidebar-left')

                <div class="col-lg-6 col-md-12 main-feed">

                    @yield('category-navigation')
                    @yield('content')

                </div>

                @include('partials.sidebar-right')

            </div>

        </div>

        @include('partials.bottom-nav')
    @endif

    @guest
        <div class="modal fade" id="interactionLoginModal" tabindex="-1" role="dialog" aria-labelledby="interactionLoginModalTitle" aria-describedby="interactionLoginModalDescription" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title fs-5" id="interactionLoginModalTitle">Log in to interact</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="interactionLoginModalDescription">
                        Please log in to like, save, or comment on this post.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <a class="btn btn-primary" href="{{ route('login', ['return_to' => request()->getRequestUri()]) }}">Log in</a>
                    </div>
                </div>
            </div>
        </div>
    @endguest

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/interactions.js') }}"></script>
    @stack('scripts')


</body>

</html>
