<!-- resources/views/appointment/datetime-barber.blade.php -->
<x-layout>
    @section('title', 'Select Date, Time & Barber')
    @vite('resources/css/appointment-css/date-time-barber.css')
    @vite(['resources/js/appointment/date-time-barber.js']) <!-- PINAKA IMPORTANTENG PART PLEASE LANG-->
    <!-- Instrument Serif Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- Inter Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Merriweather:ital,opsz,wght@0,18..144,300..900;1,18..144,300..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    
    <!-- flatpickr source -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/airbnb.css">
    
    <div class="container">
        <h2>Select Barber</h2>
        <p class="branch-info">Barbers from {{ $branch->name }} - {{ $branch->address }}</p>
        
        <form id="booking-form" action="{{ route('appointment.confirmation') }}" method="POST">
            @csrf
            <input type="hidden" name="branch_id" value="{{ $branch->id }}">
            @if(isset($services))
                @foreach($services as $service)
                    <input type="hidden" name="services[]" value="{{ $service }}">
                    <input type="hidden" name="dropdown_option_{{ $service}}" value="{{ $dropdownOptions[$service] ?? '' }}">
                @endforeach
            @endif
    
            <div class="datetime-barber-container">
                <div class="barber-selection">
                    <div class="form-group">
                        <!-- <label class="block font-medium mb-2">Select a Barber from {{ $branch->name }} - {{ $branch->address }}</label>
     -->
                        @foreach($barbers as $barber)
                            <div class="mb-4 flex items-center space-x-4">
                                <input type="radio" name="barber_id" value="{{ $barber->id }}" class="form-radio text-[#E8B931]" required>
                                
                                <!-- para lumabas yung photo ng added barber -->
                                @php $isRecommended = $barber->is_recommended ?? false; @endphp

                                <img src="{{ $barber->photo_url }}" 
                                    alt="{{ $barber->name }}" 
                                    width="100">
    
                                <label class="ml-2">
                                    {{ $barber->name }}
                                    @if ($isRecommended)
                                      <br>  <span class="recommended-label" style="color: #E8B931; font-weight: bold;">(Recommended{{ !empty($barber->matched_specialties) 
                                        ? ' - ' . implode(', ', array_map('ucwords', $barber->matched_specialties)) 
                                        : '' }})</span>
                                    @endif
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
    
                <h2>Select date and time:</h2>
                <!-- nakahide if hindi pa nakakapagselect ng barber -->
                <div class="datetime-selection" id="datetime-selection" style="display: none;">
                    <div class="form-group">
                        <label for="appointment_date">Date</label>
                        <input type="text" name="appointment_date" id="appointment_date" class="form-control" value="{{ $appointment_date }}" required>
                    </div>
                    <div class="form-group">
                        <label for="appointment_time">Time</label>
                        <select name="appointment_time" id="appointment_time" class="form-control" required>
                            @for ($hour = 8; $hour <= 20; $hour++)
                                @foreach ([0, 30] as $minute)
                                    @php
                                        $time = sprintf('%02d:%02d', $hour, $minute);
                                        $display = \Carbon\Carbon::createFromTime($hour, $minute)->format('g:i A');
                                    @endphp
                                    <option value="{{ $time }}" {{ $appointment_time == $time ? 'selected' : '' }}>{{ $display }}</option>
                                @endforeach
                            @endfor
                        </select>
                    </div>
                </div>
            </div>
    
            <button type="submit" class="btn btn-primary">Book Appointment</button>
        </form>
    
        <form action="{{ route('appointment.service') }}" method="POST">
            @csrf
            <input type="hidden" name="branch_id" value="{{ $branch->id }}">
            @if(isset($services))
                @foreach($services as $service)
                    <input type="hidden" name="services[]" value="{{ $service }}">
                @endforeach
            @endif
            <button type="submit" class="btn btn-secondary">Back to Services</button>
        </form>
    </div> 
</x-layout>    

    <!-- Loading Overlay -->
    <div id="loadingOverlay">
        <div class="loader"></div>
        <p>Booking your appointment, please wait...</p>
    </div>

    <style>
        #loadingOverlay {
            position: fixed;
            top: 0; left: 0;
            width: 100vw;   /* full viewport width */
            height: 100vh;  /* full viewport height */
            background: rgba(0,0,0,0.85);
            z-index: 99999;
            display: none;  /* hide by default */
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            font-family: 'Inter', sans-serif;
        }

        .loader {
            border: 6px solid #f3f3f3;
            border-top: 6px solid #E8B931; /* gold spinner */
            border-radius: 50%;
            width: 60px; height: 60px;
            animation: spin 1s linear infinite;
            margin-bottom: 15px;
        }
        @keyframes spin {
            100% { transform: rotate(360deg); }
        }
    </style>

<script>
document.getElementById("booking-form").addEventListener("submit", function() {
    // Show overlay
    document.getElementById("loadingOverlay").style.display = "flex";
    // Prevent scrolling in background
    document.body.style.overflow = "hidden";
});
</script>
