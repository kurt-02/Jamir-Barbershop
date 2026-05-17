<?php

namespace App\Filament\Resources\BarberResource\Pages;

use App\Filament\Resources\BarberResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Admin; 
use Illuminate\Support\Facades\Hash;

class CreateBarber extends CreateRecord
{
    protected static string $resource = BarberResource::class;
    
    protected function afterCreate(): void
    {
        $data = $this->form->getState();

        $admin = Admin::create([
            'name' => $this->record->name,
            'email' => $this->record->email,
            'password' => Hash::make($data['password']),
            'barber_id' => $this->record->id,
        ]);

        $admin->assignRole('staff'); // default role for barbers
    }
}
