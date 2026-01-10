{{-- ================================================
FILE: resources/views/partials/navbar.blade.php
================================================ --}}

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container">

        {{-- Brand --}}
        <a class="navbar-brand fw-bold text-success" href="{{ route('home') }}">
            Fahmi Store
        </a>

        {{-- Toggle --}}
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMain">

            {{-- Search --}}
            <form class="d-flex mx-auto" style="max-width: 400px; width:100%;" action="{{ route('catalog.index') }}"
                method="GET">
                <div class="input-group">
                    <input type="text" name="q" class="form-control" placeholder="Cari jersey, sepatu, apparel..."
                        value="{{ request('q') }}">
                    <button class="btn btn-outline-success">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>

            {{-- Menu --}}
            <ul class="navbar-nav ms-auto align-items-center">

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('catalog.index') }}">
                        <i class="bi bi-grid me-1"></i> Katalog
                    </a>
                </li>

                @auth
                {{-- Wishlist --}}
                <li class="nav-item">
                    <a class="nav-link position-relative" href="{{ route('wishlist.index') }}">
                        <i class="bi bi-heart"></i>
                        <span id="wishlist-count"
                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                            style="font-size:0.6rem; {{ auth()->user()->wishlists()->count() ? '' : 'display:none' }}">
                            {{ auth()->user()->wishlists()->count() }}
                        </span>
                    </a>
                </li>

                {{-- Cart --}}
                <li class="nav-item">
                    <a class="nav-link position-relative" href="{{ route('cart.index') }}">
                        <i class="bi bi-cart3"></i>
                        @php $cartCount = auth()->user()->cart?->items()->count() ?? 0; @endphp
                        @if($cartCount)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success"
                            style="font-size:0.6rem;">
                            {{ $cartCount }}
                        </span>
                        @endif
                    </a>
                </li>

                {{-- User --}}
                <li class="nav-item dropdown ms-2">
                <li class="nav-item me-2">
                    <button id="themeToggle" class="btn btn-sm btn-outline-secondary d-flex align-items-center"
                        type="button" title="Toggle Dark Mode">
                        <i class="bi bi-moon-stars-fill" id="themeIcon"></i>
                    </button>
                </li>
                <a class="nav-link dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown">
                    <img src="{{ auth()->user()->avatar_url }}" class="rounded-circle me-2" width="32">
                    <span class="d-none d-lg-inline">{{ auth()->user()->name }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profil</a></li>
                    <li><a class="dropdown-item" href="{{ route('orders.index') }}">Pesanan</a></li>

                    @if(auth()->user()->isAdmin())
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <a class="dropdown-item text-success" href="{{ route('admin.dashboard') }}">
                            Admin Panel
                        </a>
                    </li>
                    @endif

                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="dropdown-item text-danger">Logout</button>
                        </form>
                    </li>
                </ul>
                </li>
                @else
                <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Masuk</a></li>
                <li class="nav-item">
                    <a class="btn btn-success btn-sm ms-2" href="{{ route('register') }}">Daftar</a>
                </li>
                @endauth

            </ul>
        </div>
    </div>
</nav>