<x-header>
    @section('title', 'Jamir Barbershop - Barbers')
</x-header> <!-- pinalitan ko to to header instead na x-barber -->
    @vite('resources/css/barbers.css')
    <!-- Instrument Serif Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- Inter Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Merriweather:ital,opsz,wght@0,18..144,300..900;1,18..144,300..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <!-- star -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />

<body>
    <div class="team-section">
        <div class="title">
            <h1>Meet Our Team</h1>
        </div>

        <div class="barbers">
            @foreach ($barbers as $barber)
            <a href="{{ route('barber.calendar', ['barberId' => $barber->id]) }}">

                <div class="barber-card">

                    <!-- para lumabas photo ng added barber -->
                    <img src="{{ $barber->photo_url }}" alt="{{ $barber->name }}" class="barber-photo">

                    <div class="barber-info">
                        <p class="barber-name">{{ $barber->name }}</p>
                        <p class="barber-branch">
                            {{
                                match ($barber->branch_id) {
                                    1 => 'Camella',
                                    2 => 'SFL Building',
                                    default => 'Unknown Branch'
                                }
                            }}
                        </p>
                        <p class="barber-rating">
                            <span class="material-symbols-outlined star">star</span>
                            <span class="rating-value">
                                {{ $barber->reviews_avg_rating ? number_format($barber->reviews_avg_rating, 1) : 'No ratings yet' }}
                            </span>
                        </p>
                        <!-- <a href="{{ route('barber.calendar', ['barberId' => $barber->id]) }}" class="view-calendar-button">
                            View Barber
                        </a> -->
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        <div class="pagination">
            {{ $barbers->links() }}
        </div>
    </div>
</body>
