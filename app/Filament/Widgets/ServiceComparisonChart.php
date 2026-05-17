<?php

namespace App\Filament\Widgets;

use App\Models\Service;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class ServiceComparisonChart extends ChartWidget
{
    protected static ?string $heading = 'Booked Services';

    protected static ?int $sort = 5;
    
    public function getColumnSpan(): int|string|array
    {
        return [
            'default' => 12, // full width on mobile
            'md' => 6,       // half width on tablets
            'lg' => 4,       // one-third width on desktop
        ];
    }

    public string | null $filter = 'today';

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Today',
            'this_week' => 'This Week',
            'this_month' => 'This Month',
            'last_month' => 'Last Month',
        ];
    }

    protected function getData(): array
    {
        $admin = auth()->guard('admin')->user();
        $barberId = $admin->barber_id ?? null;
        $isStaff = $admin->hasRole('staff');

        $services = Service::with(['appointments' => function ($query) use ($barberId, $isStaff) {
            $query->whereIn('status', ['confirmed', 'completed']);
            if ($isStaff && $barberId) {
                $query->where('barber_id', $barberId);
            }

            if ($this->filter === 'today') {
                $query->whereDate('appointments.created_at', Carbon::today());
            } elseif ($this->filter === 'this_week') {
                $query->whereBetween('appointments.created_at', [
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek(),
                ]);
            } elseif ($this->filter === 'this_month') {
                $query->whereMonth('appointments.created_at', Carbon::now()->month)
                    ->whereYear('appointments.created_at', Carbon::now()->year);
            } elseif ($this->filter === 'last_month') {
                $query->whereMonth('appointments.created_at', Carbon::now()->subMonth()->month)
                    ->whereYear('appointments.created_at', Carbon::now()->subMonth()->year);
            }
        }])->get();

        $data = $services->map(fn($service) => $service->appointments->count());

        return [
            'datasets' => [
                [
                    'label' => 'Bookings',
                    'data' => $data,
                    'backgroundColor' => [
                        '#3B82F6',
                        '#10B981',
                        '#F59E0B',
                        '#EF4444',
                        '#8B5CF6',
                        '#EC4899',
                        '#22D3EE',
                    ],
                ],
            ],
            'labels' => $services->pluck('name'),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
