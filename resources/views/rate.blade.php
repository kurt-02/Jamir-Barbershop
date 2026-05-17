<x-header>
    @section('title', 'Jamir Barbershop - Rate a Barber')
</x-header>
@vite('resources/css/rate.css')
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

<!-- Sweet Alert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="review-container">
    <h2 class="review-title">Leave a Review</h2>

    <form action="{{ route('reviews.store') }}" method="POST" class="review-form">
        @csrf
        <div class="form-flex">
            <div class="form-group">
                <label for="barber">Choose Barber:</label>
                <select name="barber_id" id="barber" required>
                    @foreach ($barbers as $barber)
                        <option value="{{ $barber->id }}">{{ $barber->name }}</option>
                    @endforeach
                </select><br>
                <small>(Only barbers you’ve had appointments with are listed)</small>
            </div>
            <div class="form-group">
                <label>Rating:</label>
                <div class="star-rating">
                    @for ($i = 5; $i >= 1; $i--)
                        <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}">
                        <label for="star{{ $i }}">
                            <span class="material-symbols-outlined">star</span>
                        </label>
                    @endfor
                </div>
            </div>
        </div>

        <div class="form-group">
            <label>Quick Feedback:</label>
            <div class="checkbox-group">
                <label><input type="checkbox" name="quick_feedback[]" value="Clean shop"> Clean shop</label>
                <label><input type="checkbox" name="quick_feedback[]" value="Friendly barber"> Friendly barber</label>
                <label><input type="checkbox" name="quick_feedback[]" value="Great haircut"> Great haircut</label>
                <label><input type="checkbox" name="quick_feedback[]" value="On time"> On time</label>
            </div>
        </div>
        
        <div class="form-group">
            <label for="comment">Comments:</label>
            <textarea name="comment" id="comment" rows="4" placeholder="Your message..."></textarea>
        </div>
        
        <div class="form-group">
            <label>Review Anonymously?</label>
            <div class="radio-group">
                <input type="radio" name="is_anonymous" id="anon-yes" value="1">
                <label for="anon-yes">Yes</label>

                <input type="radio" name="is_anonymous" id="anon-no" value="0" checked>
                <label for="anon-no">No</label>
            </div>
        </div>
        <div class="form-group error-message" style="display:none; color:red; margin-bottom:10px; font-weight:600;">
            Please select a star rating before submitting your review.
        </div>
        <button type="submit" class="btn-submit">Submit</button>
        <a href="{{ url('/reviews')}}" 
            class="btn-back">
            Back to Reviews
        </a>
    </form>
</div>

@if ($errors->any())
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            html: `{!! implode('<br>', $errors->all()) !!}`,
            confirmButtonColor: '#E8B931'
        });
    </script>
@endif

<script>
    document.querySelector('.review-form').addEventListener('submit', function (e) {
        const rating = document.querySelector('input[name="rating"]:checked');
        const errorMsg = document.querySelector('.error-message');

        if (!rating) {
            e.preventDefault(); // stop form submission
            errorMsg.style.display = 'block';
            errorMsg.scrollIntoView({ behavior: 'smooth', block: 'center' });
        } else {
            errorMsg.style.display = 'none';
        }
    });
</script>