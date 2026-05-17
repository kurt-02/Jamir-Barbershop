<x-header>
    @section('title', 'Jamir Barbershop - Haircut Recommender')
</x-header>

<!-- Instrument Serif Font -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

<!-- Inter Font -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Merriweather:ital,opsz,wght@0,18..144,300..900;1,18..144,300..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

<!-- @vite('resources/css/face-result.css') -->

<div class="main-content">
    <div class="title">
        <h1>Haircut Recommendations</h1>
    </div>

    <div class="main">
        <div class="photo">
            @php
                $faceImages = [
                    'Square' => 'square-face.jpg',
                    'Round' => 'round-face.jpg',
                    'Oblong' => 'oblong-face.png',
                    'Heart' => 'heart-face.jpg',
                    'Triangle' => 'triangle-face.jpg',
                    'Diamond' => 'diamond-face.jpg',
                ];
            @endphp

            @if(array_key_exists($faceShape, $faceImages))
                <img src="{{ asset('images/photos/' . $faceImages[$faceShape]) }}" alt="{{ $faceShape }} face shape">
            @else
                <img src="{{ asset('images/photos/ai-face.jpg') }}" alt="Default face shape">
            @endif
        </div>


        <div class="right-side">
            <h3>Your Face Shape is <span>{{ $faceShape }}!</span></h3>
            <h4>Our barbers recommend these haircuts for your face shape:</h4>
            <div class="reco">
                <ul>
                @foreach($recommendedHaircuts as $haircut)
                    <li><strong>{{ $haircut }}</strong></li>
                @endforeach
                </ul>
                
                <div class="button">
                    <a href="{{ route('face.index') }}">Try another photo</a>
                </div>
    
                <h4>Face Analysis:</h4>
                <ul>
                    <li><strong>Face Ratio:</strong> {{ $faceRatio }}</li>
                    <li><strong>Jaw Definition:</strong> {{ $jawDefinition }}</li>
                    <li><strong>Face Width:</strong> {{ $faceWidthCategory }}</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
    body {
        background: linear-gradient(to bottom, rgba(0,0,0,0) 20%, rgba(0,0,0,0.9) 100%), url('../images/backgrounds/hair-reco-bg.png');
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center top; 
        background-attachment: fixed; 
        color: white;
    }

    .title{
        border-bottom: 2px solid white;
    }

    span{
        color: #E8B931;
    }

    .title h1{
        font-family: 'Instrument Serif', serif;
        font-optical-sizing: auto;
        font-weight: 300;
        font-style: normal;
        font-variation-settings:
        "wdth" 100;
        font-size: 5rem;
    }
    
    .reco{
        margin-left: 20px;
        font-size: 1.3rem;
        padding: 0.6rem;
    }

    h3{
        font-size: 2.5rem;
    }

    h4{
        font-size: 1.1rem
    }

    li{
        list-style-type: disc;
    }

    .main-content{
        padding: 1rem 3rem;
    }

    .main {
        display: flex;
        font-family: "Inter", sans-serif;
        font-optical-sizing: auto;
        font-style: normal;
        margin-top: 2rem;
    }

    .right-side{
        margin-left: 4rem;
        margin-top: 1rem;
        width: 60%;
    }

    .right-side h2{
        font-size: 2.5rem;
    }

    .right-side p{
        font-size: 1.2rem;
        font-weight: 200;
    }

    .button a {
        display: inline-block;
        padding: 10px 20px;
        border: 2px solid #E8B931; 
        border-radius: 30px; 
        color: #E8B931; 
        background-color: transparent;
        text-decoration: none;
        font-size: 16px;
        font-weight: 500;
        transition: all 0.3s ease;
        margin-top: 20px;
        margin-bottom: 20px;
    }

    .button a:hover {
        background-color: #E8B931;
        color: #000; 
    }

    .note{
        margin-top: 3rem;
    }

    .photo{
        width: 30%;
        height: auto;
    }

    /* Tablet view (up to 1024px wide) */
    @media (max-width: 1024px) {
        .main {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .photo {
            width: 50%;
            margin-bottom: 2rem;
        }

        .right-side {
            width: 90%;
            margin-left: 0;
        }

        .title h1 {
            font-size: 3.5rem;
        }

        h3 {
            font-size: 2rem;
        }

        .reco {
            font-size: 1.1rem;
            margin-left: 0;
        }
        
        li{
            list-style-type: none;
        }
    }

    /* Mobile view (up to 768px wide) */
    @media (max-width: 768px) {
        body {
            background-position: center;
            padding: 0 1rem;
        }

        .title h1 {
            font-size: 2.5rem;
        }

        .photo {
            width: 80%;
        }

        .right-side {
            width: 100%;
        }

        .button a {
            width: 100%;
            text-align: center;
            padding: 12px;
            font-size: 1rem;
        }

        h3 {
            font-size: 1.8rem;
        }

        h4 {
            font-size: 1rem;
        }

        .reco {
            font-size: 1rem;
            padding: 0.4rem;
        }

        li{
            list-style-type: none;
        }
    }
</style>