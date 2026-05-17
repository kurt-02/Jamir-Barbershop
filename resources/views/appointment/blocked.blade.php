<!-- no longer a feature -->
<x-header>
    @section('title', 'Booking Temporarily Blocked')
</x-header>
@vite('resources/css/appointment-css/no-show.css')

<div class="container">
    <h2>Booking Temporarily Blocked</h2>
    <div class="message">
        <p>Your last appointment was marked as a <strong>no show</strong>.</p>
        <div class="appointment-details">
            <p><strong>Last No-Show Appointment:</strong></p>
            <ul>
                <li><strong>Date:</strong> {{ \Carbon\Carbon::parse($recentNoShow->appointment_date)->format('F j, Y') }}</li>
                <li><strong>Time:</strong> {{ \Carbon\Carbon::createFromFormat('H:i', $recentNoShow->appointment_time)->format('h:i A') }}</li>
                <li><strong>Branch:</strong> {{ $recentNoShow->branch->name }} - {{ $recentNoShow->branch->address }}</li>
                <li><strong>Barber:</strong> {{ $recentNoShow->barber->name }}</li>
            </ul>
        </div>
        <p>You will be able to book a new appointment in <strong>{{ $remainingDays }}</strong> {{ Str::plural('day', $remainingDays) }}.</p>
        <div class="button-group">
            <a href="/" class="btn btn-secondary">Back to Home</a>
        </div>
    </div>
</div>
