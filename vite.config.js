import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 
                'resources/css/home.css', 'resources/css/about.css', 'resources/css/barbers.css', 'resources/css/dashboard.css',
                'resources/css/face-result.css', 'resources/css/face-upload.css', 'resources/css/login.css', 'resources/css/rate.css',
                'resources/css/register.css', 'resources/css/reviews.css', 'resources/css/services.css', 'resources/css/admin/admin.css',
                'resources/css/appointment-css/confirmation.css', 'resources/css/appointment-css/date-time-barber.css', 'resources/css/appointment-css/location.css',
                'resources/css/appointment-css/no-show.css', 'resources/css/appointment-css/reschedule.css', 'resources/css/appointment-css/reschedule-form.css',
                'resources/css/appointment-css/service.css', 'resources/css/appointment-css/steps.css', 'resources/js/appointment/date-time-barber.js', 'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
});
