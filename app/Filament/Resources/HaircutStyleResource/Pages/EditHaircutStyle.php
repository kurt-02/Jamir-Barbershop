<?php

namespace App\Filament\Resources\HaircutStyleResource\Pages;

use App\Filament\Resources\HaircutStyleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHaircutStyle extends EditRecord
{
    protected static string $resource = HaircutStyleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
