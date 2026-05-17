<x-header>

</x-header>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title></title>
        @vite('resources/css/home.css')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <!-- Instrument Serif Font -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

        <!-- Inter Font -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Merriweather:ital,opsz,wght@0,18..144,300..900;1,18..144,300..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
       
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
           
        @endif
    </head>
    <body>
        <!-- <div class="overlay-transition" id="bg-overlay"></div> -->
        <div class="content-wrapper">
            <div class="home-container">
                <div class="title-container">
                    <h1>Jamir Barbershop</h1>
                </div>
                <div class="home-buttons">
                    <a href=" {{ route('appointment.location')}}" class="btn-white">BOOK APPOINTMENT</a>
                    <a href="{{ route('face.index') }}" class="btn-secondary">GET HAIRCUT RECOMMENDATION</a>
                </div>
            </div>
            <div class="second-container" id="second-section">
                <div class="second-title-container">
                    <h1 class="separator">Check out our current promos</h1>
                    
                    <div class="offer-containers">
                        @foreach($offers as $offer)
                            <div class="offer-box-container">
                                <span class="offer-title">{{ $offer->title }}</span> 
                                <h2 class="offer-description">{{ $offer->description }}</h2>
                            </div>
                        @endforeach
                    </div>

                    <div class="loyalty-container">
                        <div id="loyaltyCardContent" style="">
                            <img src="{{ asset('images/photos/loyalty-card.png') }}" alt="Loyalty Card" class="loyalty-card-image">
                        </div>
                        <div class="loyalty-content">
                            <div class="policy-text">
                                <h3>Introducing our Loyalty Card</h3>
                                <p>Booking online just got more rewarding. Every time you book online, you’ll collect a stamp. 
                                    Once you’ve booked a set number of times, you’ll unlock special discounts just for you. 
                                    It’s our way of saying thanks for always coming back.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="third-container" id="second-section">
                <div class="third-title-container">
                    <h1 class="separator">Our current list of branches</h1>
                    
                    <div class="location-containers">
                        <div class="location-box-container">
                            <span class="location-title">Camella Branch</span> 
                            <h2 class="location-address">Camella Springville, 628 M.V. Villar Ave, Bacoor, 4102 Cavite</h2>
                        </div>
                        <div class="location-box-container">
                            <span class="location-title">SFL BLDG Branch</span> 
                            <h2 class="location-address">Blk 6, First Magdiwang Subdivision, Lot 1 Magdiwang Rd, Molino 3, Bacoor, Cavite</h2>
                        </div>
                    </div>
                </div>
        </div>
    </body>
</html>
<script>
    // prevents going back to the dashboard page after log out
    window.history.replaceState(null, document.title, window.location.href);
    window.addEventListener('popstate', function () {
        window.history.replaceState(null, document.title, window.location.href);
    });
    
    // transition
    document.addEventListener("scroll", function () {
        let scrollTop = window.scrollY;
        let maxScroll = 300; // how far you scroll before it's fully black
        let opacity = Math.min(scrollTop / maxScroll, 1);

        document.body.style.setProperty("--overlay-opacity", opacity);
    });
</script>