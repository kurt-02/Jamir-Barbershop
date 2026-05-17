<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BarberResource\Pages;
use App\Filament\Resources\BarberResource\RelationManagers;
use App\Models\Barber;
use App\Models\Branch;
use App\Models\Service;
use App\Models\Admin;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\CheckboxList;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

class BarberResource extends Resource
{
    protected static ?string $model = Barber::class;

    protected static ?string $navigationIcon = 'heroicon-o-scissors';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                TextInput::make('name')
                ->required()
                ->maxLength(255)
                ->label('Barber Name'),
                
                TextInput::make('email')
                ->label('Email')
                ->email()
                // ->required()
                ->maxLength(255),
                
                TextInput::make('password')
                ->label('Barber Account Password')
                ->password()
                ->required(fn ($livewire) => $livewire instanceof Pages\CreateBarber)
                ->dehydrateStateUsing(fn ($state) => $state) // keep raw password
                ->dehydrated(true) // make it available in form state
                ->visible(fn ($livewire) => $livewire instanceof Pages\CreateBarber),

                FileUpload::make('photo')
                ->label('Photo')
                ->disk('public') 
                ->directory('barbers') 
                ->image()
                ->imagePreviewHeight('150')
                ->preserveFilenames(false)
                ->nullable(),

                Select::make('branch_id')
                ->label('Branch')
                ->options(Branch::all()->pluck('name', 'id')->toArray())
                ->searchable()
                ->required(),

                CheckboxList::make('days_off')
                ->label('Off Days')
                ->options([
                    'Monday' => 'Monday',
                    'Tuesday' => 'Tuesday',
                    'Wednesday' => 'Wednesday',
                    'Thursday' => 'Thursday',
                    'Friday' => 'Friday',
                    'Saturday' => 'Saturday',
                    'Sunday' => 'Sunday',
                ])
                ->columns(2)
                ->required(false)
                ->default([]),

                Select::make('specialties')
                ->label('Haircut Specialties')
                ->options(function () {
                    return \App\Models\Service::where('name', 'like', '%Haircut%')
                        ->whereNotNull('dropdown_options')
                        ->get()
                        ->pluck('dropdown_options')
                        ->flatten()
                        ->unique()
                        ->values()
                        ->mapWithKeys(fn($item) => [$item => $item])
                        ->toArray();
                })
                ->searchable()
                ->multiple()
                ->required(false),

                    ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
               Tables\Columns\ImageColumn::make('photo')
                ->label('Photo')
                ->disk('barbers')
                ->getStateUsing(fn ($record) => $record->photo_url)
                ->square()
                ->size(80),

                Tables\Columns\TextColumn::make('name')->label('Barbers'), 
                Tables\Columns\TextColumn::make('email')->label('Email'), 
                Tables\Columns\TextColumn::make('branch.name')->label('Branch'),
                Tables\Columns\TextColumn::make('days_off')->label('Off Days'),
            ])
            ->filters([
                // may filter pa rito kasi hindi pa naka global filter
                SelectFilter::make('branch_id')->label('Branch')->options(Branch::all()->pluck('name', 'id')->toArray())
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function shouldRegisterNavigation(): bool{
        return !auth()->user()?->hasRole('staff');  // doesnt show this tab to the staff's account
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
            'index' => Pages\ListBarbers::route('/'),
            'create' => Pages\CreateBarber::route('/create'),
            'edit' => Pages\EditBarber::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return 'People';
    }
}
