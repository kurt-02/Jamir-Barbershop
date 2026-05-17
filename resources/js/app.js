import './bootstrap';
import 'flowbite';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Initialize Flowbite components
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all Flowbite components
    const initFlowbite = () => {
        // Initialize dropdowns
        const dropdowns = document.querySelectorAll('[data-dropdown-toggle]');
        dropdowns.forEach(dropdown => {
            new Flowbite.Dropdown(dropdown);
        });

        // Initialize drawers (sidebar)
        const drawers = document.querySelectorAll('[data-drawer-toggle]');
        drawers.forEach(drawer => {
            new Flowbite.Drawer(drawer);
        });
    };

    initFlowbite();
});

// FullCalendar setup
import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';

window.FullCalendar = {
    Calendar,
    dayGridPlugin
};
