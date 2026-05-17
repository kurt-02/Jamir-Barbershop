<x-header>
    @section('title', 'Reschedule Appointment')
</x-header>
@vite('resources/css/appointment-css/reschedule-form.css')

<!-- flatpickr source -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/airbnb.css">

<!-- Instrument Serif Font -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

<!-- Inter Font -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Merriweather:ital,opsz,wght@0,18..144,300..900;1,18..144,300..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

<div class="container">
    <h2>Reschedule Your Appointment</h2>
    <div class="message">
        <p>Select a new date & time for your appointment.</p>

        <p>Your current appointment with <strong>{{ $appointment->barber->name }}</strong> is scheduled on 
            <strong>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('F j, Y') }}</strong> 
            at 
            <strong>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}</strong>.
        </p>


        <form id="reschedule-form" action="{{ route('appointment.reschedule.submit', $appointment->id) }}" method="POST">
            @csrf

            <input type="hidden" name="barber_id" value="{{ $appointment->barber->id }}">

            <div class="form-group">
                <label for="appointment_date">New Date:</label>
                <input type="text" name="appointment_date" id="appointment_date" required>
            </div>

            <div class="form-group">
                <label for="appointment_time">Available Time:</label>
                <select name="appointment_time" id="appointment_time" required>
                    <option value="">Select time</option>
                </select>
            </div>

            <div class="button-group">
                <button type="submit" class="btn btn-primary">Confirm Reschedule</button>
                <a href="/" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<div id="loading-overlay">
    <div class="spinner"></div>
    <p>Rescheduling your appointment...</p>
</div>

<style>
#loading-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.7);
    color: white;
    z-index: 9999;
    text-align: center;
    justify-content: center;
    align-items: center;
    flex-direction: column;
}
#loading-overlay .spinner {
    border: 6px solid #f3f3f3;
    border-top: 6px solid #E8B931;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    animation: spin 1s linear infinite;
    margin-bottom: 15px;
}
@keyframes spin {
    100% { transform: rotate(360deg); }
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const appointmentDateInput = document.getElementById('appointment_date');
    const appointmentTimeSelect = document.getElementById('appointment_time');
    const rescheduleForm = document.getElementById('reschedule-form');
    const barberId = "{{ $appointment->barber->id }}";
    let availabilityData = {};

    const loadingOverlay = document.getElementById('loading-overlay');
    // ✅ Hide overlay initially
    loadingOverlay.style.display = 'none';

    let fp = flatpickr(appointmentDateInput, {
        dateFormat: "Y-m-d",
        minDate: new Date().fp_incr(1), // tomorrow onwards
        enable: [], // will be set after fetch
        onChange: function (selectedDates, dateStr) {
            updateAvailableTimes(dateStr);
        }
    });

    function updateAvailableTimes(date) {
        appointmentTimeSelect.innerHTML = '';

        if (!availabilityData[date]) {
            appointmentTimeSelect.disabled = true;
            const option = document.createElement('option');
            option.text = 'No available times';
            option.value = '';
            appointmentTimeSelect.add(option);
            return;
        }

        appointmentTimeSelect.disabled = false;

        availabilityData[date].forEach(time => {
            const option = document.createElement('option');
            option.value = time;

            const [hour, minute] = time.split(':');
            const displayTime = new Date(0, 0, 0, hour, minute).toLocaleTimeString([], {
                hour: 'numeric',
                minute: '2-digit'
            });

            option.text = displayTime;
            appointmentTimeSelect.add(option);
        });
    }

    // Load availability data for the current barber
    fetch(`/barber-availability/${barberId}?current_appointment_id={{ $appointment->id }}`)
        .then(response => response.json())
        .then(data => {
            availabilityData = data;

            const availableDates = Object.keys(availabilityData);
            fp.set('enable', availableDates);

            // Clear previous values
            fp.clear();
            appointmentTimeSelect.innerHTML = '<option value="">Select time</option>';
            appointmentTimeSelect.disabled = true;
        })
        .catch(() => {
            alert('Failed to load barber availability. Please refresh the page.');
        });

    rescheduleForm.addEventListener('submit', function (e) {
        const selectedDate = appointmentDateInput.value;
        const selectedTime = appointmentTimeSelect.value;
        const validTime = availabilityData[selectedDate]?.includes(selectedTime);

        if (!selectedDate || !validTime) {
            e.preventDefault();
            alert('Please select a valid available date and time.');
            return;
        }
        loadingOverlay.style.display = 'flex';
    });
});
</script>
