<?php

namespace App\Filament\Widgets;

use App\Models\Barber;
use App\Models\Appointment;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class BarberBookingChart extends ChartWidget
{
    protected static ?string $heading = 'Barber Bookings';

    protected static ?int $sort = 2;
    
    public function getColumnSpan(): int|string|array
    {
        return [
            'default' => 12, // full width on mobile
            'md' => 6,       // half width on tablets
            'lg' => 4,       // one-third width on desktop
        ];
    }

    
    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'indexAxis' => 'y', // horizontal bars
            'plugins' => [
                'legend' => ['display' => false], // hide legend
            ],
            'scales' => [
                'x' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Number of Bookings',
                    ],
                ],
            ],
        ];
    }
        
    protected function getFilters(): ?array
    {
        return [
            'today' => 'Today',
            'week' => 'This Week',
            'month' => 'This Month',
            'last_month' => 'Last Month',
        ];
    }

    protected function getDefaultFilter(): ?string
    {
        return 'today';
    }

    protected function getColorForBarber($id): string
    {
        $colors = [
            '#FEC147', // Gold
            '#1E90FF', // Green
            '#32CD32', // Blue
            '#FF8C00', // Deep Orange
            '#8A2BE2', // Purple
            '#E91E63', // Pink
            '#00BCD4', // Cyan
            '#FF9800', // Orange
        ];

        // Ensure the index wraps around if there are more barbers than colors
        return $colors[$id % count($colors)];
    }

    protected function getData(): array
    {
        $filter = $this->filter ?? $this->getDefaultFilter(); 
    
        $barbers = Barber::all();
        $labels = [];
        $data = [];
        $colors = [];
    
        foreach ($barbers as $index => $barber) {
            $query = Appointment::where('barber_id', $barber->id)
                ->whereIn('status', ['confirmed', 'completed']);
    
            if ($filter === 'today') {
                $query->whereDate('created_at', Carbon::today());
            } elseif ($filter === 'week') {
                $query->whereBetween('created_at', [
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek(),
                ]);
            } elseif ($filter === 'month') {
                $query->whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year);
            } elseif ($filter === 'last_month') {
                $query->whereMonth('created_at', Carbon::now()->subMonth()->month)
                    ->whereYear('created_at', Carbon::now()->subMonth()->year);
            }
    
            $labels[] = $barber->name;
            $data[] = $query->count();
            $colors[] = $this->getColorForBarber($index);
        }
    
        return [
            'datasets' => [
                [
                    'label' => 'Bookings',
                    'data' => $data,
                    'backgroundColor' => $colors,
                    'borderColor' => $colors,
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    public static function canView(): bool
    {
        $admin = app('auth')->guard('admin')->user();
        return !$admin?->hasRole('staff');
    }

}
