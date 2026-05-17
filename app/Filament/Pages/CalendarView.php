<?php

namespace App\Filament\Pages;

use App\Models\Appointment;
use Filament\Pages\Page;

class CalendarView extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static string $view = 'filament.pages.calendar-view';
    protected static ?string $title = 'Calendar';
    protected static ?string $navigationGroup = 'Appointments';

    public array $events = [];

    public function mount(): void
    {
        $admin = auth()->user();

        $barberColors = [
            1 => '#1E90FF', // Barber 1
            2 => '#32CD32', // Barber 2
            3 => '#FF8C00', // Barber 3
            4 => '#8A2BE2', // Barber 4 
            4 => '#E91E63', // Barber 5
            // ayusin mo pa to for final version
        ];

        $query = Appointment::with('barber')
            ->where('status', 'confirmed');

        if ($admin->barber_id !== null) {
            $query->where('barber_id', $admin->barber_id);
        }

        $appointments = $query->get();

        $this->events = $appointments->map(function ($appointment) use($barberColors) {
            // Combine date + time into a Carbon instance
            $start = \Carbon\Carbon::parse("{$appointment->appointment_date} {$appointment->appointment_time}");
            $barberId = $appointment->barber_id;
            $color = $barberColors[$barberId] ?? '#FF0000'; 

            return [
                'title' => optional($appointment->barber)->name ?? 'Appointment',
                'start' => $start->toIso8601String(),
                'color' => $color,
            ];
        })->toArray();
    }

    public static function shouldRegisterNavigation(): bool
    {
        $admin = auth()->user();
        return $admin && ($admin->barber_id === null || $admin->barber_id !== null);
    }
}