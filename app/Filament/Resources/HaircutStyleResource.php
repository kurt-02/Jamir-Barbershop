<?php

namespace App\Filament\Resources;

use App\Models\HaircutStyle;
use Filament\Forms;
use Filament\Resources\Resource;
use App\Filament\Resources\HaircutStyleResource\Pages;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;

class HaircutStyleResource extends Resource
{
    protected static ?string $model = HaircutStyle::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->required(),
                
            Forms\Components\FileUpload::make('image')
                ->label('Photo')
                ->disk('public') // store in storage/app/public
                ->disk('haircuts') // stored under storage/app/public/haircuts
                ->image()
                ->imagePreviewHeight('150')
                ->preserveFilenames(false)
                ->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            Tables\Columns\ImageColumn::make('image')
            ->getStateUsing(fn ($record) => $record->photo_url),
            Tables\Columns\TextColumn::make('name'),
        ])
        ->actions([
            Tables\Actions\EditAction::make(),   
            Tables\Actions\DeleteAction::make(), 
        ])
        ->bulkActions([
            Tables\Actions\DeleteBulkAction::make(), 
        ]);
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHaircutStyles::route('/'),
            'create' => Pages\CreateHaircutStyle::route('/create'),
            'edit' => Pages\EditHaircutStyle::route('/{record}/edit'),
        ];
    }
    
    public static function shouldRegisterNavigation(): bool{
        return !auth()->user()?->hasRole('staff');  // doesnt show this tab to the staff's account
    }
}