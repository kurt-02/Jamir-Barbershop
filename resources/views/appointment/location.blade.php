<!-- resources/views/appointment/location.blade.php -->
<x-layout>
    @section('title', 'Select Location')
    @vite('resources/css/appointment-css/location.css')
    <!-- Instrument Serif Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- Inter Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Merriweather:ital,opsz,wght@0,18..144,300..900;1,18..144,300..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    
    <div class="container">
        <h2>Select a Branch</h2>
        <form action="{{ route('appointment.service') }}" method="POST">
            @csrf
            <div class="branch-options">
                @foreach($branches as $branch)
                    <label class="branch-card">
                        <input type="radio" name="branch_id" value="{{ $branch->id }}">
                        <div class="branch-content">
                            <img src="{{ asset('/images/icons/gold-location.svg') }}" alt="Location Icon" class="branch-icon" />
                            <div>
                                <div class="branch-name">{{ $branch->name }}</div>
                                <div class="branch-address">{{ $branch->address }}</div>
                            </div>
                        </div>
                    </label>
                @endforeach
            </div>
            <div id="branch-error" class="text-danger mb-3" style="display: none; color: red;">
                Please select a branch before proceeding.
            </div>
    
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
    
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('form');
        const errorDiv = document.getElementById('branch-error');
    
        form.addEventListener('submit', function (e) {
            const selected = form.querySelector('input[name="branch_id"]:checked');
            if (!selected) {
                e.preventDefault();
                errorDiv.style.display = 'block';
            } else {
                errorDiv.style.display = 'none';
            }
        });
    });
    </script>
</x-layout>



