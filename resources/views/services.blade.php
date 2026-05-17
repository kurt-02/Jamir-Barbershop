<x-header>
    @section('title', 'Jamir Barbershop - Services')
</x-header>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jamir Barbershop - Services</title>
    @vite('resources/css/services.css')
    <!-- Instrument Serif Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- Inter Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Merriweather:ital,opsz,wght@0,18..144,300..900;1,18..144,300..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    
    <!-- Swiper JS and CSS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
</head>
<body>
    <div class="content-wrapper">
        <div class="services-texts">
            <h1>Featured Services</h1>
        </div>
    </div>

    <!-- Swiper Container -->
    <div class="swiper mySwiper">
        <div class="swiper-wrapper">
            <div class="swiper-slide">
                <img src="{{ asset('/images/photos/low-fade.jpg')}}" alt="Cut 1">
                <h1 class= "haircut">Low Fade</h1>
            </div>
            <div class="swiper-slide">
                <img src="{{ asset('/images/photos/mid-fade.jpg')}}" alt="Cut 2">
                <h1 class= "haircut">Mid Fade</h1>
            </div>
            <div class="swiper-slide">
                <img src="{{ asset('/images/photos/high-fade.jpg')}}" alt="Cut 3">
                <h1 class= "haircut">High Fade</h1>
            </div>
            <div class="swiper-slide">
                <img src="{{ asset('/images/photos/warrior-cut.jpg')}}" alt="Cut 3">
                <h1 class= "haircut">Warrior Cut</h1>
            </div>
            <div class="swiper-slide">
                <img src="{{ asset('/images/photos/16-guard.jpg')}}" alt="Cut 3">
                <h1 class= "haircut">16 Guard</h1>
            </div>
            <div class="swiper-slide">
                <img src="{{ asset('/images/photos/blowout.jpg')}}" alt="Cut 3">
                <h1 class= "haircut">Blowout</h1>
            </div>
            <div class="swiper-slide">
                <img src="{{ asset('/images/photos/low-taper.jpg')}}" alt="Cut 3">
                <h1 class= "haircut">Low Taper</h1>      
            </div>
            <div class="swiper-slide">
                <img src="{{ asset('/images/photos/mid-taper.jpg')}}" alt="Cut 3">
                <h1 class= "haircut">Mid Taper</h1>
            </div>
            <div class="swiper-slide">
                <img src="{{ asset('/images/photos/high-taper.jpg')}}" alt="Cut 3">
                <h1 class= "haircut">High Taper</h1>
            </div>
            <div class="swiper-slide">
                <img src="{{ asset('/images/photos/burst-fade.jpg')}}" alt="Cut 3">
                <h1 class= "haircut">Burst Fade</h1>
            </div>
            <div class="swiper-slide">
                <img src="{{ asset('/images/photos/two-block.jpg')}}" alt="Cut 3">
                <h1 class= "haircut">Two Block</h1>
            </div>
            <div class="swiper-slide">
                <img src="{{ asset('/images/photos/crew-cut.jpg')}}" alt="Cut 3">
                <h1 class= "haircut">Crew Cut</h1>
            </div>
            @foreach($haircuts as $cut)
                <div class="swiper-slide">
                    <img src="{{ asset('storage/haircuts/' . $cut->image) }}" alt="{{ $cut->name }}">
                    <h1 class="haircut">{{ $cut->name }}</h1>
                </div>
            @endforeach
        </div>

        <!-- Navigation and Pagination -->
            <!-- <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div> -->
        <div class="swiper-pagination"></div>
    </div>

    <div class="services-content2">
        <div class="services-boxes" id="second-section">

            {{-- CUTS --}}
            <div class="service-group">
                <h2 class="box-title">Haircuts</h2>
                <div class="service-card">
                    @foreach($services->take(2) as $service)
                        <div class="service-item">
                            <div class="service-title">
                                <span>{{$service->name}}</span>
                                <span class="price">₱ {{$service->price}}</span>
                            </div>
                            <div class="service-description">
                                @if ($loop->index == 0)
                                    <p class="description">(SFL Building Branch) Classic haircuts for men 12 or older.</p>
                                @elseif ($loop->index == 1)
                                    <p class="description">Classic haircuts for kids 10 or younger.</p>
                                @endif
                            </div>
                        </div>
                        @if ($loop->first)
                            <div class="service-item">
                                <div class="service-title">
                                    <span>Regular Haircut</span>
                                    <span class="price">₱ 120.00</span>
                                </div>
                                <div class="service-description">
                                    <p class="description">(Camella Branch) Classic haircuts for men 12 or older.</p>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            {{-- COLORS --}}
            <div class="service-group">
                <h2 class="box-title">Hair Colors</h2>
                <div class="service-card">
                    @foreach($services->slice(2, 1) as $service)
                        <div class="service-item">
                            <div class="service-title">
                                <span>{{$service->name}}</span>
                                <span class="price">₱ {{$service->price}}</span>
                            </div>
                            <div class="service-description">
                                @if ($loop->index == 0)
                                    <p class="description">Full hair coverage.</p>
                                @elseif ($loop->index == 1)
                                    <p class="description">Colored dye with full hair coverage.</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- SHAVES --}}
            <div class="service-group">
                <h2 class="box-title">Shaves</h2>
                <div class="service-card">
                    @foreach($services->slice(3, 5) as $service)
                        <div class="service-item">
                            <div class="service-title">
                                <span>{{$service->name}}</span>
                                <span class="price">₱ {{$service->price}}</span>
                            </div>
                            <div class="service-description">
                                @if ($loop->index == 0)
                                    <p class="description">Clean and precise trim for beard.</p>
                                @elseif ($loop->index == 1)
                                    <p class="description">Complete beard and mustache shave.</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</body>

<script>
    const swiper = new Swiper(".mySwiper", {
        loop: true,
        centeredSlides: false,
        spaceBetween: 30,
        slidesPerView: 3, 
        slidesPerGroup: 3, 
        autoplay: {
            delay: 2000,
            disableOnInteraction: false,
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        breakpoints: {
            0: { 
                slidesPerView: 1, 
                slidesPerGroup: 1 
            }, 
            768: { 
                slidesPerView: 1, 
                slidesPerGroup: 1 
            }, 
            1024: { 
                slidesPerView: 3, 
                slidesPerGroup: 3 
            } 
        }
    });
</script>

<script>
    // background fade transition
    document.addEventListener("scroll", function () {
        let scrollTop = window.scrollY;
        let maxScroll = 200;
        let opacity = Math.min(scrollTop / maxScroll, 1);
        document.body.style.setProperty("--overlay-opacity", opacity);
    });
</script>
</html>
