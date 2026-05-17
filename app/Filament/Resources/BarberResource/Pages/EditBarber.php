<?php

namespace App\Filament\Resources\BarberResource\Pages;

use App\Filament\Resources\BarberResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBarber extends EditRecord
{
    protected static string $resource = BarberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // If photo key is missing or null, keep the old photo
        if (!isset($data['photo']) || $data['photo'] === null) {
            $data['photo'] = $this->record->photo;
        }

        return $data;
    }
}
