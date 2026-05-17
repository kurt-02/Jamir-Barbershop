<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Instrument Serif Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <title>Login</title>


    @vite(['resources/css/login.css'])
</head>
<body>
<div class="container">
    <div class="login-section">
        <form method="POST" action="{{ route('login') }}" class="form-box">
            @csrf

             <div class="logo-center">
                <a href="/">
                    <img src="{{ asset('/images/icons/logo.svg') }}" alt="Logo"/>
                </a>
            </div>

            <h2>Log In</h2>

            <div>
                <x-input-label for="login" :value="__('Email or Contact Number')" class="input-label mt-4" />
                <x-text-input id="login" class="text-input" type="text" name="login" :value="old('login')" required autofocus autocomplete="username"/>
                <x-input-error :messages="$errors->get('login')" class="input-error" />
            </div>

            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" class="input-label" />
                <x-text-input id="password" class="text-input" type="password" name="password" required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="input-error" />
            </div>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="forgot-password">Forgot password?</a>
            @endif

            <x-primary-button class="button-primary" id="login-btn">
                <span id="btn-spinner" class="spinner hidden"></span>
                <span>LOG IN</span>
            </x-primary-button>

            <div class="register-link">
                Don't have an account? <a href="{{ route('register') }}">Register Here</a>
            </div>
        </form>
    </div>

    <div class="image-section" style="background-image: url('{{ asset('/images/backgrounds/login-register-bg.png') }}');">
        <div class="welcome-text">
            <p>Welcome to</p>
            <h1>Jamir<br>Barbershop</h1>
        </div>
    </div>
</div>
</body>
</html>

<!-- loading animation -->
<script>
document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector("form");
    const spinner = document.getElementById("btn-spinner");

    form.addEventListener("submit", () => {
        // Disable button so it can't be clicked multiple times
        form.querySelector("button").disabled = true;

        // Show spinner
        spinner.classList.remove("hidden");
    });
});
</script>
