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

    <title>Register</title>

    @vite(['resources/css/register.css'])
</head>
<body>
<div class="container">
    <!-- Left Form Section -->
    <div class="register-section">
        <form method="POST" action="{{ route('register') }}" class="form-box">
            @csrf
            
            <h2>Register</h2>
            
            <div class="logo-center">
                <a href="/">
                    <img src="{{ asset('/images/icons/logo.svg') }}" alt="Logo"/>
                </a>
            </div>

            <!-- Name -->
            <div>
                <x-input-label for="name" :value="__('Name')" class="input-label mt-4" />
                <x-text-input id="name" class="text-input" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="input-error" />
            </div>

            <!-- Email -->
            <div class="mt-4">
                <p class="note-text">Note: You must provide a valid email or contact number for appointment confirmations.</p>
                <x-input-label for="email" :value="__('Email')" class="input-label" />
                <x-text-input id="email" class="text-input" type="email" name="email" placeholder="juandelacruz@gmail.com" :value="old('email')" />
                <x-input-error :messages="$errors->get('email')" class="input-error" />
            </div>

            <!-- Contact Number -->
            <div class="mt-4">
                <!--<p class="note-text">Note: If your SIM card is not Globe or DITO, kindly skip this form and register using your email address instead.</p>-->
                <x-input-label for="contact_number" :value="__('Contact Number')" class="input-label" />
                
                <div style="display: flex; align-items: center;">
                    <span style="padding: 0.8rem; border: 3px solid #ccc; border-right: none; border-radius: 6px 0 0 6px; font-size: 14px;">
                        +63
                    </span>
                    <x-text-input 
                        id="contact_number" 
                        class="text-input" 
                        type="text" 
                        name="contact_number" 
                        placeholder="9123456789" 
                        :value="old('contact_number')" 
                        style="border-radius: 0 6px 6px 0; flex: 1;"
                    />
            </div>

    <x-input-error :messages="$errors->get('contact_number')" class="input-error" />
    @if ($errors->has('contact'))
        <div class="input-error" style="margin-top: 0.5rem;">
            {{ $errors->first('contact') }}
        </div>
    @endif
</div>


            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" class="input-label" />
                <x-text-input id="password" class="text-input" type="password" name="password" required />
                <p class="note-text">Note: Password should be at least 8 characters and contain one special character</p>
                <x-input-error :messages="$errors->get('password')" class="input-error" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="input-label" />
                <x-text-input id="password_confirmation" class="text-input" type="password" name="password_confirmation" required />
                <x-input-error :messages="$errors->get('password_confirmation')" class="input-error" />
            </div>

            <x-primary-button class="button-primary mt-4" id="register-btn">
                <span id="btn-spinner" class="spinner hidden"></span>
                <span>REGISTER</span>
            </x-primary-button>

            <div class="register-link">
                Already have an account? <a href="{{ route('login') }}">Log in</a>
            </div>
        </form>
    </div>

    <!-- Right Background Section -->
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
