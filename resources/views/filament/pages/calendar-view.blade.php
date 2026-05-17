<x-filament::page>
    <div id="calendar-wrapper">
        <div id="calendar"></div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const calendarEl = document.getElementById('calendar');

                const calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    events: @json($events),
                    editable: false,
                    eventTimeFormat: {
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: true
                    },
                    selectable: false,
                    allDaySlot: false,
                    nowIndicator: true,
                    slotMinTime: '08:00:00',
                    slotMaxTime: '20:00:00',
                    height: 'auto',
                    dayMaxEvents: 1,
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },

                    // Show "Booked Times" if multiple events in a day
                    eventContent: function(arg) {
                        const eventsOnDay = arg.view.calendar.getEvents().filter(e =>
                            e.startStr.slice(0,10) === arg.event.startStr.slice(0,10)
                        );

                        if (eventsOnDay.length === 1) {
                            let time = FullCalendar.formatDate(arg.event.start, {
                                hour: '2-digit',
                                minute: '2-digit',
                                hour12: true
                            });
                            return { html: `<div class="custom-event">${time}</div>` };
                        }

                        // More than 1 appointment
                        return { html: `<div class="custom-event">Booked Times</div>` };
                    },

                    // When showing the popover (+n more), display full event time/details
                    eventDidMount: function(info) {
                        if (info.el.closest('.fc-popover')) {
                            let time = FullCalendar.formatDate(info.event.start, {
                                hour: '2-digit',
                                minute: '2-digit',
                                hour12: true
                            });
                            info.el.innerHTML = `${time} - ${info.event.title}`;
                        }
                    }
                });

                calendar.render();
            });
        </script>
    @endpush

    <style>
        /* Wrapper */
        #calendar-wrapper {
            padding: 1.5rem;
        }

        /* Calendar container */
        #calendar {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            min-height: 600px;
        }

        /* FullCalendar default styling */
        .fc { font-size: 0.90rem; }
        .fc-button {
            color: white !important;
            border-color: white !important;
            background-color: rgb(228, 161, 28) !important;
        }

        /* Tablet (≤1024px) */
        @media (max-width: 1024px) {
            #calendar { min-height: 500px; }
            .fc-toolbar { flex-direction: column; gap: 8px; align-items: center; }
            .fc-toolbar-title { font-size: 1.25rem; text-align: center; }
            .fc-button { padding: 5px 10px; font-size: 0.9rem; }
        }

        /* Mobile (≤768px) */
        @media (max-width: 768px) {
            #calendar { min-height: 400px; }
            .fc-toolbar { flex-direction: column; gap: 6px; align-items: center; }
            .fc-toolbar-title { font-size: 1rem; }
            .fc-button { padding: 4px 8px; font-size: 0.8rem; }
            .fc-daygrid-event {
                font-size: 0.7rem;
                text-align: center;
                white-space: normal !important;
                word-break: break-word !important;
            }
        }

        .custom-event {
            font-size: 0.75rem;
            font-weight: 500;
            text-align: center;
        }
    </style>
</x-filament::page>
