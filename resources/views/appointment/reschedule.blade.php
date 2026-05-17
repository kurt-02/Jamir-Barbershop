<!-- resources/views/appointment/reschedule.blade.php -->
<x-header>
     @section('title', 'Reschedule Appointment')
</x-header>
@vite('resources/css/appointment-css/reschedule.css')
<!-- Instrument Serif Font -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

<!-- Inter Font -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Merriweather:ital,opsz,wght@0,18..144,300..900;1,18..144,300..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

<div class="container">
    <h2>You Already Have an Appointment</h2>
    <div class="message">
        <p class="question">Would you like to reschedule it?</p>
        <div class="appointment-details">
            <p><strong>Current Appointment Details:</strong></p>
            <ul>
                <div class="first-row">
                    <li><strong>Date:</strong> {{ $activeAppointment->appointment_date }}</li>
                    <li><strong>Time:</strong> {{ $activeAppointment->appointment_time }}</li>
                </div>
                <div class="second-row">
                    <li><strong>Service(s):</strong>
                        @foreach($activeAppointment->services as $service)
                            {{ $service->name }}
                            @if($service->pivot->dropdown_option)
                                ({{ $service->pivot->dropdown_option }})
                            @endif
                            @if (!$loop->last), @endif
                        @endforeach
                    </li>
                    <li><strong>Duration:</strong> {{ $activeAppointment->total_duration_minutes }} minutes</li>
                </div>
                <div class="third-row">
                    <li><strong>Branch:</strong> {{ $activeAppointment->branch->name }} - {{ $activeAppointment->branch->address }}</li>
                    <li><strong>Barber:</strong> {{ $activeAppointment->barber->name }}</li>
                </div>
            </ul>
        </div>
        <div class="button-group">
            <a href="{{ route('appointment.reschedule.form', $activeAppointment->id) }}" class="btn btn-primary">Reschedule Appointment</a>
            <a href="/" class="btn btn-secondary">Back to Home</a>
        </div>
    </div>
</div> 