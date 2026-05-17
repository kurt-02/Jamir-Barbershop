<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', '')</title>
    @vite('resources/css/app.css')
    @vite('resources/css/appointment-css/steps.css')
    <!-- Instrument Serif Font -->  
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- Inter Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Merriweather:ital,opsz,wght@0,18..144,300..900;1,18..144,300..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <style>
        #confirm-button:hover {
            background-color: #B28F4D !important;
        }
    </style>
</head>
<body data-in-booking="{{ request()->is('appointment/*') ? 'true' : 'false' }}" style="background: url('{{ asset('/images/backgrounds/appointment-bg.png') }}') no-repeat center top; background-size: cover; background-attachment: fixed;">

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

    <!-- Mobile Fullscreen Menu -->
    <div id="mobile-menu" class="hidden fixed inset-0 bg-black text-white flex flex-col items-center justify-center space-y-6 text-lg z-[999]">
        <a href="/" class="{{ request()->is('/') ? 'active': '' }}">HOME</a>
        <a href="/barbers" class="{{ request()->is('barbers') ? 'active': '' }}">BARBERS</a>
        <a href="/about" class="{{ request()->is('about') ? 'active': '' }}">ABOUT US</a>
        <a href="/services" class="{{ request()->is('services') ? 'active': '' }}">SERVICES</a>
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


    <!-- Steps indication -->
@php
    $currentStep = request()->path();
    $steps = [
        'appointment/location' => '1. Branch',
        'appointment/service' => '2. Services',
        'appointment/datetime-barber' => '3. Schedule',
        'appointment/confirmation' => '4. Summary',
    ];

    $stepReached = false;
@endphp

<div class="appointment-wrapper" style="margin-top: 6rem;">
    <div class="steps">
        @foreach ($steps as $path => $label)
            @php
                $isActive = !$stepReached;
                if ($currentStep === $path) {
                    $stepReached = true;
                }
            @endphp
            <h1 class="{{ $isActive ? 'active' : '' }}">{{ $label }}</h1>
        @endforeach
    </div>

    <div class="content" id="main-content">
        {{ $slot }}
    </div>
</div>


    <!-- Leave Booking Modal -->
<div id="info-popup" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed inset-0 z-50 w-full flex items-center justify-center">
  <div class="relative p-4 w-full max-w-lg md:h-auto">
      <div class="relative p-4 bg-white rounded-lg shadow md:p-8">
          <div class="mb-4 text-sm font-light text-gray-500">
              <h3 class="mb-3 text-2xl font-bold text-gray-700">Leave Booking Process?</h3>
              <p>
                  You are currently in the booking process. Are you sure you want to leave? Your progress will be lost.
              </p>
          </div>
          <div class="justify-end items-center pt-0 space-y-4 sm:flex sm:space-y-0">

                <div class="items-center space-y-4 sm:space-x-4 sm:flex sm:space-y-0">
                    <button id="confirm-button" type="button" class="py-2 px-4 w-full text-sm font-medium text-center text-white rounded-lg sm:w-auto bg-[#FEC147] hover:bg-[#d6a23b]">Confirm</button>
                    <button id="close-modal" type="button"  class="py-2 px-4 w-full text-sm font-medium text-white rounded-lg sm:w-auto bg-[#dc3545] hover:bg-[#921a26] focus:ring-4 focus:outline-none focus:ring-primary-300 hover:text-white-900 focus:z-10 dark:focus:ring-gray-600">Cancel</button>
                </div>
          </div>
      </div>
  </div>
</div>

    <script>
document.addEventListener("DOMContentLoaded", () => {
    // --- Hamburger toggle ---
    // --- Hamburger toggle ---
const menuBtn = document.getElementById("menu-btn");
const mobileMenu = document.getElementById("mobile-menu");
menuBtn.addEventListener("click", () => {
    mobileMenu.classList.toggle("hidden");
});

// Close menu when clicking a link
document.querySelectorAll("#mobile-menu a").forEach(link => {
    link.addEventListener("click", () => {
        mobileMenu.classList.add("hidden");
        menuBtn.innerHTML = "&#9776;";
    });
});


    // --- Booking modal logic ---
    if (document.body.dataset.inBooking === "true" && !window.location.pathname.includes('confirmation')) {
        const modal = document.getElementById("info-popup");
        const confirmButton = document.getElementById("confirm-button");
        const closeButton = document.getElementById("close-modal");
        const mainContent = document.getElementById('main-content');
        let pendingHref = null;

        // Push a dummy state to catch browser back button
        history.pushState({ booking: true }, '', window.location.href);

        function showModal() {
            modal.classList.remove('hidden');
            mainContent.classList.add('blur-sm');
            document.body.style.overflow = 'hidden';
        }

        function hideModal() {
            modal.classList.add('hidden');
            mainContent.classList.remove('blur-sm');
            document.body.style.overflow = 'auto';
            pendingHref = null;
        }

        window.addEventListener('popstate', () => {
            if (!window.location.pathname.includes('confirmation')) {
                history.pushState({ booking: true }, '', window.location.href);
                showModal();
            }
        });

        document.querySelectorAll("a:not([href^='#']):not([href*='/appointment'])").forEach(link => {
            const href = link.getAttribute("href");
            if (!href) return;

            link.addEventListener("click", (e) => {
                e.preventDefault();
                pendingHref = link.href;
                showModal();
            });
        });

        // Prevent clicks on form inputs from being hijacked
        document.querySelectorAll('input, select, textarea').forEach(input => {
            input.addEventListener('click', e => e.stopPropagation());
        });

        // Modal buttons
        closeButton.addEventListener("click", hideModal);
        confirmButton.addEventListener("click", () => {
            if (pendingHref) {
                window.location.href = pendingHref;
            } else {
                history.back();
            }
        });

        // Escape key closes modal
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                hideModal();
            }
        });

        window.addEventListener("beforeunload", function (e) {
            if (!window.location.pathname.includes('appointment')) {
                e.preventDefault();
                e.returnValue = '';
            }
        });
    }
});
</script>

</body>
</html>
