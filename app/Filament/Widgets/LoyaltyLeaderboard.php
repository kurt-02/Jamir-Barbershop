<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

class LoyaltyLeaderboard extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';
    
    protected static ?string $heading = 'Loyalty Leaderboard';
    
    protected static ?int $sort = 4;
    
    public function getColumnSpan(): int|string|array
    {
        return [
            'default' => 12, // mobile
            'md' => 6,       // tablet
            'lg' => 4,       // desktop
        ];
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->query(
                User::query()
                ->whereHas('appointments') // Only users with at least 1 appointment
                ->withCount([
                    'appointments as completed_appointments_count' => function ($query) {
                        $query->where('status', 'completed');
                    }
                ])
                ->orderByDesc('completed_appointments_count')
                ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Customer')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('completed_appointments_count')
                    ->label('Completed Appointments')
                    ->sortable()
                    ->badge()
                    ->color(function ($state) {
                        if ($state >= 12) {
                            return 'success';
                        }
                        if ($state >= 6) {
                            return 'warning';
                        }
                        return 'primary';
                    }),

                Tables\Columns\TextColumn::make('loyalty_status')
                    ->label('Loyalty Status')
                    ->getStateUsing(function ($record) {
                        $count = $record->completed_appointments_count;

                        if ($count >= 12) {
                            return '12 / 12 (Eligible for discount)';
                        }
                        if ($count >= 6) {
                            return $count . ' / 12 (Eligible for 50% discount)';
                        }
                        return $count . ' / 12';
                    })
                    ->badge()
                    ->color(function ($state) {
                        if (str_contains($state, '12 / 12')) {
                            return 'success';
                        }
                        if (str_contains($state, 'Eligible for 50% discount')) {
                            return 'warning';
                        }
                        return 'primary';
                    }),
                ]);
    }
    
    public static function canView(): bool
    {
        $admin = app('auth')->guard('admin')->user();
        return !$admin?->hasRole('staff');
    }
}
