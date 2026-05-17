<p>New Appointment Received</p>
<p>Dear {{ $appointment->barber->name }},</p>
<p>You have a new appointment scheduled:</p>

<p><strong>Client Name:</strong> {{ $appointment->client_name }}</p>
<p><strong>Date:</strong> {{ $appointment->appointment_date }}</p>
<p><strong>Time:</strong> {{ $appointment->appointment_time }}</p>
<p><strong>Branch:</strong> {{ $appointment->branch->name }}</p>

<p><strong>Services:</strong></p>
@foreach($appointment->services as $service)
    <p style="margin-left: 20px;">
        {{ $service->name }}
        @if($service->pivot->dropdown_option)
            (Option: {{ $service->pivot->dropdown_option }})
        @endif
    </p>
@endforeach

<p>
    <a href="{{ route('appointment.complete', ['token' => $appointment->completion_token]) }}" style="background: #C69F55; color: #fff; padding: 10px 15px; text-decoration: none; border-radius: 5px;">
        Mark as Complete
    </a>
</p>


<p>Please be ready to serve your client!</p>
<p>Thank you.</p>
