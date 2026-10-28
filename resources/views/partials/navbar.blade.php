<nav class="navbar bg-white shadow-sm p-2">
    <div class="container d-flex align-items-center justify-content-between gap-2">
        <a href="{{ route('feed.index') }}" class="logo-text text-decoration-none fw-bold flex-shrink-0">
            သောင်းပြောင်းထွေရာ
        </a>

        <form class="d-none d-lg-flex flex-grow-1 px-4" action="{{ route('feed.search') }}" method="GET" role="search">
            <label class="visually-hidden" for="desktop-search">Search</label>
            <div class="input-group">
                <input id="desktop-search" class="form-control" type="search" name="q" value="{{ request('q') }}"
                    maxlength="150" placeholder="Search">
                <button class="btn btn-outline-primary" type="submit" aria-label="Search"><i class="bi bi-search"
                        aria-hidden="true"></i></button>
            </div>
        </form>

        <div class="d-flex align-items-center gap-2">
            @guest
            <a href="{{ route('login') }}" class="btn btn-outline-primary d-none d-lg-block fs-6">သင့်နာမည်ကို
                ရှာပါ။</a>
            @endguest

            @auth
            @if(auth()->user()->role === 'admin')
            <a href="{{ route('admin.dashboard') }}" class="btn btn-dark d-none d-lg-block">Admin</a>
            @else
            <a href="{{ route('user.profile') }}"
                class="btn btn-outline-primary d-none d-lg-block">{{ auth()->user()->name }}</a>
            @endif
            @endauth

            <button class="btn btn-light d-lg-none nav-icon" id="mobileMenuBtn" type="button" aria-label="Open menu"
                aria-controls="mobileMenu" aria-expanded="false">
                <i class="bi bi-list" aria-hidden="true"></i>
            </button>
        </div>
    </div>

    <div class="mobile-menu d-lg-none p-2 bg-white shadow-sm w-75" id="mobileMenu" style="display:none;">
        <form class="mb-2" action="{{ route('feed.search') }}" method="GET" role="search">
            <label class="visually-hidden" for="mobile-search">Search</label>
            <div class="input-group">
                <input id="mobile-search" class="form-control" type="search" name="q" value="{{ request('q') }}"
                    maxlength="150" placeholder="Search">
                <button class="btn btn-outline-primary" type="submit" aria-label="Search"><i class="bi bi-search"
                        aria-hidden="true"></i></button>
            </div>
        </form>

        @guest
        <a href="{{ route('register') }}" class="btn btn-primary w-100">Register</a>
        @endguest
        @auth
        @if(auth()->user()->role === 'admin')
        <a href="{{ route('admin.dashboard') }}" class="btn btn-dark w-100 mb-2">Admin dashboard</a>
        @else
        <a href="{{ route('user.profile') }}" class="btn btn-primary w-100">{{ auth()->user()->name }}</a>
        @endif
        @endauth
    </div>
</nav>