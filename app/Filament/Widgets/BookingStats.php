<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use App\Models\User;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BookingStats extends BaseWidget
{
    protected static ?int $sort = 1;
    
    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
        ];
    }

    protected function getStats(): array
    {
        $admin = auth()->guard('admin')->user();
        $barberId = $admin->barber_id;
        $isStaff = $admin->hasRole('staff');

        $appointmentQuery = Appointment::query()->whereIn('status', ['confirmed', 'completed']);

        if ($isStaff && $barberId) {
            $appointmentQuery->where('barber_id', $barberId);
        }
        
        $mostAvailedService = $appointmentQuery->clone()
            ->join('appointment_service', 'appointments.id', '=', 'appointment_service.appointment_id')
            ->join('services', 'services.id', '=', 'appointment_service.service_id')
            ->select('services.name', DB::raw('COUNT(*) as total'))
            ->groupBy('services.name')
            ->orderByDesc('total')
            ->first();
            
        $mostAvailedServiceName = $mostAvailedService->name ?? 'N/A';
        $mostAvailedServiceCount = $mostAvailedService->total ?? 0;

        $stats = [
            Stat::make("Today's Bookings", $appointmentQuery->clone()->whereDate('created_at', Carbon::today())->count())
                ->description('Bookings made today')
                ->icon('heroicon-o-calendar')
                ->color('success'),

            Stat::make("This Month's Bookings", $appointmentQuery->clone()->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count())
                ->description('Bookings made this month')
                ->icon('heroicon-o-calendar-days')
                ->color('info'),

            Stat::make("This Year's Bookings", $appointmentQuery->clone()->whereYear('created_at', now()->year)->count())
                ->description('Bookings made this year')
                ->icon('heroicon-o-calendar')
                ->color('warning'),
                
            Stat::make('Most Availed Service', $mostAvailedServiceName)
                ->description($mostAvailedServiceCount . ' bookings')
                ->icon('heroicon-o-scissors')
                ->color('primary'),
        ];

        // Only show "Total Users" to non-staff (e.g., admins)
        if (!$isStaff) {
            $stats[] = Stat::make('Total Users', User::count())
                ->description('All registered users')
                ->icon('heroicon-o-user-group')
                ->color('success')
                ->chart([1, 3, 5, 10, 20, 40]);
        }

        return $stats;
    }

    public function getColumnSpan(): int | string | array
    {
        return [
            'default' => 12,
            'md' => 6,
        ];
    }
}
