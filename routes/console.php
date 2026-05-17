<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Console\Commands\SendAppointmentReminders;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command(SendAppointmentReminders::class)->everyMinute();
Schedule::command('queue:work --stop-when-empty')->everyMinute()->withoutOverlapping();