<nav class="navbar bg-white shadow-sm p-2">

    <div class="container d-flex align-items-center justify-content-between">

        <a href="{{ route('feed.index') }}" class="logo-text text-decoration-none fw-bold">
            သောင်းပြောင်းထွေရာ
        </a>

        <div class="d-none d-lg-block flex-grow-1 px-4">

            <input class="form-control w-100" placeholder="ရှာဖွေရန်...">

        </div>

        <div class="d-flex align-items-center gap-2">

            <!-- <button class="btn btn-outline-primary d-none d-lg-block">
                စာရင်းသွင်းပါ။
            </button> -->
            @guest
            <a href="{{ route('register') }}" class="btn btn-outline-primary d-none d-lg-block">
                စာရင်းသွင်းပါ။
            </a>
            @endguest

            @auth

            @if(auth()->check() && auth()->user()->role === 'admin')

            <a href="{{ route('admin.dashboard') }}" class="btn btn-dark d-none d-lg-block">
                Admin
            </a>
            @else

            <a href="{{ route('user.profile') }}" class="btn btn-outline-primary d-none d-lg-block">
                {{ Auth::user()->name }}
            </a>

            @endif

            @endauth
            <button class="btn btn-light d-lg-none nav-icon" id="mobileMenuBtn">
                <i class="bi bi-list"></i>
            </button>

        </div>

    </div>

    <div class="mobile-menu d-lg-none p-2 bg-white shadow-sm" id="mobileMenu" style="display:none;">

        <input class="form-control mb-2" placeholder="ရှာဖွေရန်...">

        @guest
        <a href="{{ route('register') }}" class="btn btn-primary w-100">
            စာရင်းသွင်းပါ။
        </a>
        @endguest

        @auth

        @if(auth()->check() && auth()->user()->role === 'admin')

        <a href="{{ route('admin.dashboard') }}" class="btn btn-dark w-100 mb-2">
            Admin Dashboard
        </a>
        @else

        <a href="{{ route('user.profile') }}" class="btn btn-primary w-100">
            {{ Auth::user()->name }}
        </a>

        @endif

        @endauth

    </div>

</nav>