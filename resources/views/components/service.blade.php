<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Jamir Barbershop - Home')</title>
     @vite('resources/css/services.css')
</head>
<body>
    <nav>
        <div class="nav-left">
            <ul>
                <a href="/" class="{{ request()->is('/') ? 'active': '' }}">HOME</a>
                <a href="/services" class="{{ request()->is('services') ? 'active': '' }}">SERVICES</a>
                <a href="/barbers" class="{{ request()->is('barbers') ? 'active': '' }}">BARBERS</a>
                <a href="/about" class="{{ request()->is('about') ? 'active': '' }}">ABOUT US</a>
                <a href="/reviews" class="{{ request()->is('reviews') ? 'active': '' }}">REVIEWS</a>
            </ul>
        </div>

        <div class="nav-center">
            <img src="{{ Vite::asset('resources/images/icons/logo.svg') }}" alt="Logo" />
        </div>

        <div class="nav-right">
            <ul>
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn-dashboard">ACCOUNT</a>
                    @else
                        @if (Route::has('login'))
                            <a href="{{ route('login') }}" class = "btn-login">LOG IN</a>
                        @endif
                    @endauth
                @endif
            </ul>
        </div>
    </nav>
</body>
</html>