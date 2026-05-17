<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReviewResource\Pages;
use App\Filament\Resources\ReviewResource\RelationManagers;
use App\Models\Review;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BooleanColumn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $admin = auth()->guard('admin')->user();

        $query = parent::getEloquentQuery();

        if ($admin->hasRole('staff') && $admin->barber_id) {
            $query->where('barber_id', $admin->barber_id);
        }

        return $query;
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            TextColumn::make('user.name')
                ->label('User')
                ->formatStateUsing(function ($state, $record) {
                    return $record->is_anonymous ? '👤' : ($state ?? 'N/A');
                })
                ->sortable(),
            TextColumn::make('barber.name')->label('Barber')->sortable(),
            TextColumn::make('rating')->sortable(),
            TextColumn::make('comment')->limit(30)->tooltip(fn ($record) => $record->comment),
            TextColumn::make('created_at')->dateTime('M d, Y')->label('Submitted'),
        ])
        ->actions([
            Tables\Actions\DeleteAction::make()
            ->visible(function () {
                $admin = auth()->guard('admin')->user();
                // Hide delete bulk action for staff accounts
                return !$admin->hasRole('staff');
            }),
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
            'index' => Pages\ListReviews::route('/'),
            'create' => Pages\CreateReview::route('/create'),
            'edit' => Pages\EditReview::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return 'People';
    }
}
