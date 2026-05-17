<x-header>
    @section('title', 'Barber Profile')
    <!-- Instrument Serif Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- Inter Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Merriweather:ital,opsz,wght@0,18..144,300..900;1,18..144,300..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <div class="max-w-4xl mx-auto mt-10">
        <div class="p-4">
            <h1 class="custom-heading mb-10 text-white border-b-2 border-white font-['Instrument_Serif'] text-left">
                Booked Appointments for {{ $barber->name }}
            </h1>
            <div id="calendar"></div>
        </div>

        <a href="{{ url('/barbers')}}" 
           class="inline-block mt-4 mb-5 px-4 py-2 bg-transparent text-white border border-gray-400 font-['Inter'] rounded hover:bg-[#FFFF] hover:text-black transition">
            Back to Barbers
        </a>
    </div>

    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet" />
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const calendarEl = document.getElementById('calendar');

                const calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    height: 'auto',
                    dayMaxEvents: 1,
                    events: @json($events),
                    displayEventTime: false,

                    eventContent: function(arg) {
            const eventsOnDay = arg.view.calendar.getEvents().filter(e =>
                e.startStr.slice(0,10) === arg.event.startStr.slice(0,10)
            );

            // If only 1 event exists on this day, show its time
            if (eventsOnDay.length === 1) {
                let time = FullCalendar.formatDate(arg.event.start, {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: true
                });
                return { html: `<div class="custom-event">${time}</div>` };
            }

            // If multiple events, just show "Booked Times"
            return { html: `<div class="custom-event">Booked Times</div>` };
            },
            eventDidMount: function(info) {
                // If event is shown inside the popover
                if (info.el.closest('.fc-popover')) {
                    let time = FullCalendar.formatDate(info.event.start, {
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: true
                    });
                    info.el.innerHTML = `<div class="custom-event">${time}</div>`;
                }
            }

                    });
                    calendar.render();
                }); 
        </script>

    @endpush

    <style>
/* Default (desktop) */

.custom-heading {
    font-size: 2rem;
}

.fc-toolbar-title {
    font-size: 1.5rem;
    color: white;
    font-family: 'Instrument Serif', serif;
}

.fc .fc-button {
    background: transparent;
    border: 1px solid #fff;
    color: #fff;
    font-family: 'Inter', sans-serif;
    border-radius: 6px;
    padding: 6px 12px;
    transition: all 0.3s ease;
}

.fc .fc-button:hover {
    background: #fff;
    color: #000;
}

.fc-daygrid-event {
    white-space: normal !important;  /* allow wrapping */
    overflow: hidden;
    text-overflow: ellipsis;
    font-size: 0.9rem;  /* base font size */
    padding: 2px 4px;
    border-radius: 6px;
}

/* Change event popover background */
.fc-popover {
    background-color: #1e1e1e !important; /* dark background */
    color: #fff !important; /* text color */
}

/* Change the header (date area) */
.fc-popover-header {
    background-color: #121212 !important; /* yellow header */
    color: white !important; /* black text */
    font-weight: bold;
}

/* Change the close button (x) */
.fc-popover-close {
    color: #E8B931 !important;
}

.custom-event {
    font-size: 0.6rem;  /* adjust to what you want */
    font-weight: 500;    /* optional */
    text-align: center;
}



/* Tablet (≤1024px) */
@media (max-width: 1024px) {
    .custom-heading {
        font-size: 1.5rem;
    }

    .fc-toolbar-title {
        font-size: 1.25rem;
        text-align: center;
    }
    
    a.inline-block {
        display: flex;
        text-align: center;
        justify-content: center;
        width: 100%;
    }

    .fc .fc-button {
        padding: 5px 10px;
        font-size: 0.9rem;
    }
}

/* Mobile (≤768px) */
@media (max-width: 768px) {
    .fc-toolbar {
        flex-direction: column;
        gap: 8px;
        align-items: center;
    }

    .fc-toolbar-title {
        font-size: 1rem;
    }

    .fc .fc-button {
        font-size: 0.8rem;
        padding: 4px 8px;
    }
    
    .custom-heading {
        font-size: 1.5rem;
    }

    .fc-daygrid-event {
        display: block !important;
        width: 100% !important;
        max-width: 100% !important;
        white-space: normal !important;
        word-break: break-word !important;
        text-align: center !important;
        font-size: 0.75rem !important;
        line-height: 1.2 !important;
    }
}
</style>
</x-header>
