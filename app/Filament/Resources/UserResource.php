<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    
    public static function getLabel(): ?string
    {
        return 'Customer';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\TextInput::make('email')
                    ->email(),
                Forms\Components\DateTimePicker::make('email_verified_at'),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->required(),
                Forms\Components\TextInput::make('contact_number'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) { // para mafilter yung users and appointments based sa barber's account
                $admin = auth()->guard('admin')->user();

                if ($admin->hasRole('staff')) {
                    $barberId = $admin->barber_id;

                    $query->whereHas('appointments', function ($q) use ($barberId) {
                        $q->where('barber_id', $barberId);
                    });
                }

                return $query;
            })
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->visible(function () {
                        $admin = auth()->guard('admin')->user();
                        return !$admin->hasRole('staff'); 
                    }),
                Tables\Columns\TextColumn::make('contact_number')
                    ->searchable()
                    ->visible(function () {
                        $admin = auth()->guard('admin')->user();
                        return !$admin->hasRole('staff'); 
                    }),
                Tables\Columns\TextColumn::make('loyalty_progress')
                    ->label('Loyalty Progress')
                    ->getStateUsing(function ($record) {
                        // Count completed appointments for the user
                        $completedAppointments = $record->appointments()
                            ->where('status', 'completed') // adjust column name if different
                            ->count();

                        if ($completedAppointments >= 12) {
                            return '12 / 12 (Eligible for discount)';
                        }

                        if ($completedAppointments >= 6) {
                            return $completedAppointments . ' / 12 (Eligible for discount)';
                        }

                        return $completedAppointments . ' / 12';
                    })
                    ->badge()
                    ->color(function ($state) {
                        if (str_contains($state, '12 / 12')) {
                            return 'success'; // Full milestone
                        }
                        if (str_contains($state, 'Eligible for 50% discount')) {
                            return 'warning'; // Half milestone
                        }
                        return 'primary'; // Still progressing
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                // no edit functions sa users
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                    ->visible(function () {
                        $admin = auth()->guard('admin')->user();
                        // Hide delete bulk action for staff accounts
                        return !$admin->hasRole('staff');
                    }),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return 'People';
    }
}
