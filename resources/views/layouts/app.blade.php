{{-- ================================================
FILE: resources/views/layouts/app.blade.php
FUNGSI: Master layout halaman customer
================================================ --}}

<!DOCTYPE html>
<html lang="id" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- SEO --}}
    <title>@yield('title', 'Assalaam Football Store') - {{ config('app.name') }}</title>
    <meta name="description"
        content="@yield('meta_description', 'Assalaam Football Store - Jersey, apparel, dan merchandise sepak bola')">

    {{-- Favicon --}}
    <link rel="icon" href="{{ asset('favicon.ico') }}">

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>

<body>

    {{-- NAVBAR --}}
    @include('partials.navbar')

    {{-- FLASH MESSAGE --}}
    <div class="container">
        @include('partials.flash-messages')
    </div>

    {{-- CONTENT --}}
    <main class="min-vh-100">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    @include('partials.footer')

    {{-- SCRIPT --}}
    @stack('scripts')

    <script>
        async function toggleWishlist(productId) {
            try {
                const token = document.querySelector('meta[name="csrf-token"]').content;

                const response = await fetch(`/wishlist/toggle/${productId}`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": token,
                    },
                });

                if (response.status === 401) {
                    window.location.href = "/login";
                    return;
                }

                const data = await response.json();

                if (data.status === "success") {
                    updateWishlistUI(productId, data.added);
                    updateWishlistCounter(data.count);
                }
            } catch (error) {
                console.error(error);
            }
        }

        function updateWishlistUI(productId, isAdded) {
            const buttons = document.querySelectorAll(`.wishlist-btn-${productId}`);

            buttons.forEach(btn => {
                const icon = btn.querySelector("i");
                if (isAdded) {
                    icon.classList.remove("bi-heart");
                    icon.classList.add("bi-heart-fill", "text-danger");
                } else {
                    icon.classList.remove("bi-heart-fill", "text-danger");
                    icon.classList.add("bi-heart");
                }
            });
        }

        function updateWishlistCounter(count) {
            const badge = document.getElementById("wishlist-count");
            if (!badge) return;

            badge.innerText = count;
            badge.style.display = count > 0 ? "inline-block" : "none";
        }
    </script>

    {{-- THEME TOGGLE --}}
    <script>
        document.addEventListener("DOMContentLoaded", () => {
                const html = document.documentElement;
                const toggleBtn = document.getElementById("themeToggle");
                const icon = document.getElementById("themeIcon");

                if (!toggleBtn) return;

                const savedTheme = localStorage.getItem("theme") || "light";
                setTheme(savedTheme);

                toggleBtn.addEventListener("click", () => {
                    const newTheme = html.getAttribute("data-theme") === "dark" ? "light" : "dark";
                    setTheme(newTheme);
                });

                function setTheme(theme) {
                    html.setAttribute("data-theme", theme);
                    localStorage.setItem("theme", theme);

                    if (theme === "dark") {
                        icon.classList.replace("bi-moon-stars-fill", "bi-sun-fill");
                    } else {
                        icon.classList.replace("bi-sun-fill", "bi-moon-stars-fill");
                    }
                }
            });
    </script>

</body>

</html>