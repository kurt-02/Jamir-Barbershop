<x-header>
    @section('title', 'Jamir Barbershop - About')
</x-header>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    @vite('resources/css/about.css')
    <!-- Instrument Serif Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- Inter Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Merriweather:ital,opsz,wght@0,18..144,300..900;1,18..144,300..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <h1 class="title">Our Story</h1>
        <div class="contents">
            <div class="left">
                <p class="our-story">Jamir Barbershop – a concept barbershop in Bacoor, Cavite, established in 2024 – offers classic haircuts, tapers, fades, beard trims, shaves, and kiddie haircuts. 
                    </p>
                    <p>Featuring a nostalgic 90’s-inspired ambiance, our skilled and friendly barbers provide exceptional service for clients of all ages. We also offer special discounts for senior citizens and PWDs.</p><br>

                <div class="contact-info">
                    <div class="time">
                        <img src="{{ asset('/images/icons/Clock.svg')}}" alt="Time Icon" class="icon">
                        <span>Daily from 8:00 AM to 8:00 PM</span>
                    </div>
                    <div class="phone">
                        <img src="{{ asset('/images/icons/Phone.svg')}}"alt="Phone Icon" class="icon">
                        <span>+63 912 345 6789</span>
                    </div>
                </div>
            </div>
            <div class="right">
                <img src="{{ asset('images/photos/aboutimage1.jpg') }}" alt="" class="image">
            </div>
        </div>
</body>
</html>
