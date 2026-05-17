<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use App\Models\Barber;
use App\Models\Review;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TopRatedBarbers extends BaseWidget
{
    protected static ?string $heading = 'Top Rated Barbers';
    
    protected int|string|array $columnSpan = 'full';
    
    protected static ?int $sort = 3;
    
    public function getColumnSpan(): int|string|array
    {
        return [
            'default' => 12, // mobile
            'md' => 6,       // tablet
            'lg' => 4,       // desktop
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Barber::query()
                    ->select('barbers.id', 'barbers.name')
                    ->join('reviews', 'barbers.id', '=', 'reviews.barber_id')
                    ->where('reviews.approved', true)
                    ->groupBy('barbers.id', 'barbers.name')
                    ->selectRaw('AVG(reviews.rating) as average_rating, COUNT(reviews.id) as review_count')
                    ->orderByDesc('average_rating')
                    ->orderByDesc('review_count')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Barber'),
                Tables\Columns\TextColumn::make('average_rating')
                    ->label('Avg Rating')
                    ->formatStateUsing(fn ($state) => number_format($state, 1))
                    ->sortable(),
                Tables\Columns\TextColumn::make('review_count')
                    ->label('Reviews')
                    ->sortable(),
            ]);
    }

    public static function canView(): bool
    {
        $admin = app('auth')->guard('admin')->user();
        return !$admin?->hasRole('staff');
    }
}
