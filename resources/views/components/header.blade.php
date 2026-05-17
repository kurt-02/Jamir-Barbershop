<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Jamir Barbershop')</title>
    @vite('resources/css/app.css')
    @stack('styles')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuBtn = document.getElementById('menu-btn');
            const mobileMenu = document.getElementById('mobile-menu');

            menuBtn.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
                mobileMenu.classList.toggle('show');
            });
        });
    </script>
</head>
<body>
    <nav class="site-nav">
        <div class="nav-left hidden lg:flex">
            <a href="/" class="{{ request()->is('/') ? 'active': '' }}">HOME</a>
            <a href="/barbers" class="{{ request()->is('barbers') ? 'active': '' }}">BARBERS</a>
            <a href="/about" class="{{ request()->is('about') ? 'active': '' }}">ABOUT US</a>
            <a href="/services" class="{{ request()->is('services') ? 'active': '' }}">SERVICES</a>
            <a href="/reviews" class="{{ request()->is('reviews') ? 'active': '' }}">REVIEWS</a>
        </div>

        <div class="nav-center">
            <img src="{{ asset('/images/icons/logo.svg') }}" alt="Logo" />
        </div>

        <div class="nav-right hidden lg:flex">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn-dashboard">{{ Auth::user()->name }}</a>
                @else
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}" class="btn-login">LOG IN</a>
                    @endif
                @endauth
            @endif
        </div>

        <!-- Hamburger Icon (only on mobile) -->
        <div class="lg:hidden flex items-center z-50">
            <button id="menu-btn" class="text-white text-3xl focus:outline-none">
                &#9776;
            </button>
        </div>
    </nav>

    <!-- Fullscreen Mobile Menu -->
    <div id="mobile-menu" class="hidden fixed inset-0 bg-black text-white flex flex-col items-center justify-center space-y-6 text-lg z-40">
        <a href="/" class="{{ request()->is('/') ? 'active': '' }}">HOME</a>
        <a href="/services" class="{{ request()->is('services') ? 'active': '' }}">SERVICES</a>
        <a href="/barbers" class="{{ request()->is('barbers') ? 'active': '' }}">BARBERS</a>
        <a href="/about" class="{{ request()->is('about') ? 'active': '' }}">ABOUT US</a>
        <a href="/reviews" class="{{ request()->is('reviews') ? 'active': '' }}">REVIEWS</a>
        @if (Route::has('login'))
            @auth
                <a href="{{ url('/dashboard') }}" class="btn-dashboard uppercase">{{ Auth::user()->name }}</a>
            @else
                @if (Route::has('login'))
                    <a href="{{ route('login') }}" class="btn-login">LOG IN</a>
                @endif
            @endauth
        @endif
    </div>

    <main>
        {{ $slot }}
    </main>

    @stack('scripts')
</body>
</html>
