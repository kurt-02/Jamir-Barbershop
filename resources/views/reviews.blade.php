<x-header>
    @section('title', 'Jamir Barbershop - Reviews')
</x-header>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite('resources/css/reviews.css')
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
</head>
<body>
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: 'Thank you!',
                    text: @json(session('success')),
                    confirmButtonColor: '#FEC147',
                });
            });
        </script>
    @endif


    <div class="reviews-wrapper">
        <h2 class="title">Customer Reviews</h2>

        <div class="second-layer">
            <!-- search here -->

            <a href="{{ route('rate')}}" class="rate">
                <button>Add review</button>
            </a>

            <form method="GET" action="{{ route('reviews') }}" class="filter-form"> 
                <!-- <label for="barber_id">Filter by Barber:</label> -->
                <select name="barber_id" id="barber_id" onchange="this.form.submit()">
                    <option value="">All Barbers</option>
                    @foreach($barbers as $barber)
                        <option value="{{ $barber->id }}" {{ request('barber_id') == $barber->id ? 'selected' : '' }}>
                            {{ $barber->name }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="reviews-container">
            @forelse ($reviews as $review)
                <div class="review-card">
                    <div class="quote">❝</div>
                    <strong class="barber-name">{{ $review->barber->name ?? 'Unknown Barber' }}</strong>
                    <p class="message">
                        {{ $review->comment ?? 'No comment provided.' }}
                    </p>
                    <div class="review-footer">
                        <div>
                            <br>
                            <div class="star-display">
                                @for ($i = 1; $i <= 5; $i++)
                                    <span class="star {{ $i <= $review->rating ? 'filled' : '' }}">
                                        <span class="material-symbols-outlined">star</span>
                                    </span>
                                @endfor
                            </div>
                            <br>
                            <small class="by-user">
                                By: {{ $review->is_anonymous ? 'Anonymous' : ($review->user->name ?? 'Unknown') }}
                            </small>
                        </div>
                    </div>
                </div>
            @empty
                <p>No reviews yet.</p>
            @endforelse
        </div>

        <div class="pagination">
            {{ $reviews->appends(request()->query())->links() }}
        </div>
    </div>
</body>
</html>
