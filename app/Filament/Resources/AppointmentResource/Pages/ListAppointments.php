<?php

namespace App\Filament\Resources\AppointmentResource\Pages;

use App\Filament\Resources\AppointmentResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\SelectFilter;
use App\Models\Branch;

class ListAppointments extends ListRecords
{
    protected static string $resource = AppointmentResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function getTabs(): array
    {
        return [
            'active' => Tab::make('Active')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->whereIn('status', ['confirmed', 'pending'])
                )
                ->badge(fn () =>
                    static::getResource()::getModel()::whereIn('status', ['confirmed', 'pending'])->count()
                ),

            'finished' => Tab::make('Finished')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->whereIn('status', ['completed', 'no_show', 'canceled', 'expired', 'slot_taken'])
                )
                ->badge(fn () =>
                    static::getResource()::getModel()::whereIn('status', ['completed', 'no_show', 'cancelled', 'expired'. 'slot_taken'])->count()
                ),
        ];
    }
}
