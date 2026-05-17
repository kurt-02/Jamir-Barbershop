<?php

namespace App\Filament\Resources\ServiceResource\Pages;

use App\Filament\Resources\ServiceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditService extends EditRecord
{
    protected static string $resource = ServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    // 
    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (isset($data['dropdown_options'])) {
            if (is_string($data['dropdown_options'])) {
                // Try to split by comma and clean values
                $data['dropdown_options'] = array_map(
                    fn ($item) => trim($item, " \t\n\r\0\x0B\"'[]"),
                    explode(',', $data['dropdown_options'])
                );
            }
        }
        return $data;
    }
}
