<?php

namespace App\Filament\Resources\HaircutStyleResource\Pages;

use App\Filament\Resources\HaircutStyleResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions;

class ListHaircutStyles extends ListRecords
{
    protected static string $resource = HaircutStyleResource::class;
    
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
