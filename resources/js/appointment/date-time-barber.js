document.addEventListener("DOMContentLoaded", function () {
    let availabilityData = {};
    
    const barberRadios = document.querySelectorAll('input[name="barber_id"]');
    const datetimeSection = document.getElementById('datetime-selection');
    const appointmentDateInput = document.getElementById('appointment_date');
    const appointmentTimeSelect = document.getElementById('appointment_time');
    const bookingForm = document.getElementById('booking-form');
    const bookButton = bookingForm.querySelector('button[type="submit"]');

    bookButton.disabled = true; // Disable "Book Appointment" initially

    // Initialize Flatpickr with empty enabled dates
    let fp = flatpickr(appointmentDateInput, {
        dateFormat: "Y-m-d",
        minDate: new Date().fp_incr(1), // Disable past and today's date
        enable: [], // Will be filled dynamically
        onChange: function (selectedDates, dateStr) {
            updateAvailableTimes(dateStr);
        }
    });

    // Function to update time slots based on selected date
    function updateAvailableTimes(date) {
        appointmentTimeSelect.innerHTML = '';

        if (!availabilityData[date]) {
            appointmentTimeSelect.disabled = true;
            const option = document.createElement('option');
            option.text = 'No available times';
            option.value = '';
            appointmentTimeSelect.add(option);
            bookButton.disabled = true;
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

        bookButton.disabled = !appointmentTimeSelect.value;
    }

    // Enable or disable book button when time is changed
    appointmentTimeSelect.addEventListener('change', function () {
        const selectedDate = appointmentDateInput.value;
        const selectedTime = appointmentTimeSelect.value;
        const isValid = availabilityData[selectedDate]?.includes(selectedTime);
        bookButton.disabled = !isValid;
    });

    // When a barber is selected
    barberRadios.forEach(radio => {
        radio.addEventListener('change', function () {
            if (this.checked) {
                datetimeSection.style.display = 'block';

                // Fetch availability for selected barber
                fetch(`/barber-availability/${this.value}`)
                    .then(res => res.json())
                    .then(data => {
                        availabilityData = data;

                        const availableDates = Object.keys(availabilityData);
                        fp.set('enable', availableDates);
                        fp.clear();

                        appointmentTimeSelect.innerHTML = '';
                        appointmentTimeSelect.disabled = true;
                        bookButton.disabled = true;
                    })
                    .catch(() => {
                        datetimeSection.style.display = 'none';
                        alert('Failed to load barber availability. Please try again.');
                    });
            }
        });
    });

    // Prevent booking if no valid time is selected
    bookingForm.addEventListener('submit', function (e) {
        const selectedDate = appointmentDateInput.value;
        const selectedTime = appointmentTimeSelect.value;
        const validTime = availabilityData[selectedDate]?.includes(selectedTime);

        if (!selectedDate || !validTime) {
            e.preventDefault();
            alert('Please select a valid available date and time before booking.');
        }
    });
});
