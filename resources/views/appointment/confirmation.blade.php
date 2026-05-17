<!-- resources/views/appointment/confirmation.blade.php -->
<x-layout>
     @section('title', 'Appointment Details')
     @vite('resources/css/appointment-css/confirmation.css')
     <!-- Instrument Serif Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- Inter Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Merriweather:ital,opsz,wght@0,18..144,300..900;1,18..144,300..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
     
     <div class="container">
         <h2>Appointment Details Summary</h2>
         <ul class="ul-confirmation">
             <li><strong>Name:</strong> {{ Auth::user()->name }}</li>
             <li><strong>Contact:</strong> 
                 @if(Auth::user()->email)
                     Email - {{ Auth::user()->email }}
                     @if(Auth::user()->contact_number)
                         <br>
                     @endif
                 @endif
                 @if(Auth::user()->contact_number)
                     Phone - {{ Auth::user()->contact_number }}
                 @endif
             </li>
             <li><strong>Branch:</strong> {{ $appointment->branch->name }} - {{ $appointment->branch->address }} </li>
             <li><strong>Services: </strong> 
                 @foreach($appointment->services as $service)
                     {{ $service->name }} 
                     @if($service->pivot->dropdown_option)
                         ({{ $service->pivot->dropdown_option }})
                     @endif
                 @endforeach
             </li>
             <li><strong>Barber:</strong> {{ $appointment->barber->name }}</li>
             <li><strong>Date & Time:</strong> {{ $appointment->appointment_date }} at {{ $appointment->appointment_time }}</li>
         </ul>
         <div class="bottom-content">
             <p>Your appointment slot is currently pending! Please view your email or phone number to confirm your appointment.</p>
             <a href="/" class="home-page-btn">Back to Home</a>
         </div>
     </div>
</x-layout>

