<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceResource\Pages;
use App\Filament\Resources\ServiceResource\RelationManagers;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
// for edit values
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\CheckboxList;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                 TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->minValue(0),

                TagsInput::make('dropdown_options')
                    ->label('Options')
                    ->helperText('Add or remove options for this service')
                    ->separator(',')
                    ->dehydrated(fn ($state) => filled($state))
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                Tables\Columns\TextColumn::make('name')
                    ->label('Service Name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('price')
                    ->money('PHP', true),

                Tables\Columns\BadgeColumn::make('dropdown_options')
                    ->label('Options')
                    ->getStateUsing(function ($record) {
                        $options = $record->dropdown_options;

                        if (is_array($options)) {
                            return $options;
                        }

                        $decoded = json_decode($options, true);

                        if (is_array($decoded)) {
                            return $decoded;
                        }

                        return $options ? [$options] : [];
                    })
                    ->color('gray'),

            ])
            ->filters([
                //
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
        return !auth()->user()?->hasRole('staff'); // doesnt show this tab to the staff's account
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
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }
}
