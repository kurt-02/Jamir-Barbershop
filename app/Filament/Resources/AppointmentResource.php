<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AppointmentResource\Pages;
use App\Filament\Resources\AppointmentResource\RelationManagers;
use App\Models\Appointment;
use App\Models\Branch; 
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\ActionGroup;
use App\Filament\Resources\AppointmentResource\Pages\ListAppointments as ListAppointmentsPage;


class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;

    protected static ?string $navigationIcon = 'heroicon-o-queue-list';

    protected static ?string $navigationLabel = 'Appointment Details';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) { // para mafilter yung appointments based sa barber's account
                $admin = Auth::guard('admin')->user();

                if ($admin->hasRole('staff')) {
                    $query->where('barber_id', $admin->barber_id);
                }

                $query->orderByRaw("
                    CASE 
                        WHEN status = 'confirmed' THEN 0
                        WHEN status = 'pending' THEN 1
                        WHEN status = 'completed' THEN 2
                        WHEN status = 'no_show' THEN 3
                        WHEN status = 'expired' THEN 4
                        ELSE 5
                    END
                ")->orderBy('appointment_date')
                ->orderBy('appointment_time');

                return $query;
            })
            ->columns([
                //
                Tables\Columns\TextColumn::make('client_name'),

                Tables\Columns\TextColumn::make('appointment_date')
                ->label('Date & Time')
                ->formatStateUsing(function ($record) {
                    return $record->appointment_date . ' - ' . $record->appointment_time;
                }),
                
                Tables\Columns\TextColumn::make('branch.name'),

                Tables\Columns\TextColumn::make('barber.name'),
                
                Tables\Columns\TextColumn::make('services_with_options')
                ->label('Services')
                ->getStateUsing(function ($record) {
                    return $record->services->map(function ($service) {
                        $option = $service->pivot->dropdown_option;
                        return $option ? "{$service->name} ({$option})" : $service->name;
                    })->implode(', ');
                })
                ->badge(),
                // may branch pa rito kasi hindi pa naka global filter
                Tables\Columns\TextColumn::make('status')
                ->label('Status')
                ->sortable()
                ->searchable()
                ->formatStateUsing(fn ($state) => ucfirst($state))
                ->badge() // always show as badge
                ->color(fn ($state) => match ($state) {
                    'pending'    => 'warning',   // yellow
                    'confirmed'  => 'success',   // green
                    'slot_taken' => 'danger',    // red
                    'completed'  => 'success',   // blue
                    'no_show'    => 'gray',      // gray
                    'expired'    => 'gray', // purple
                    'canceled'  => 'danger',     // light gray
                    default      => 'primary',
                }),
                Tables\Columns\TextColumn::make('cancel_reason')
                ->label('Reason for Cancellation')
            ])
            ->filters([
                 SelectFilter::make('status')
                ->label('Status')
                ->options([
                    'pending'    => 'Pending',
                    'confirmed'  => 'Confirmed',
                    'slot_taken' => 'Slot Taken',
                    'completed'  => 'Completed',
                    'no_show'    => 'No Show',
                    'canceled'  => 'Cancelled',
                    'expired'    => 'Expired',
                ])
                ->hidden(fn ($livewire) => $livewire->activeTab === null ? false : (
                    $livewire->activeTab === 'active' 
                        ? false  // show all statuses in Active tab
                        : false  // show all statuses in Finished tab
                )),

                SelectFilter::make('branch_id')
                ->label('Branch')
                ->options(Branch::pluck('name', 'id')->toArray()),
                ])
                ->actions([
                Tables\Actions\ActionGroup::make([
                    // mark appointments as complete
                    Tables\Actions\Action::make('markAsComplete')
                        ->label('Complete')
                        ->requiresConfirmation()
                        ->visible(fn ($record) => $record->status === 'confirmed')
                        ->action(function ($record) {
                            $record->status = 'completed';
                            $record->save();
                        })
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->extraAttributes([
                            'class' => 'mb-2 border border-green-600 text-green-600 hover:bg-green-50 rounded-md px-4 py-10',
                        ]),
                        // mark appointments as no show
                    Tables\Actions\Action::make('markNoShow')
                        ->label('No-Show')
                        ->requiresConfirmation()
                        ->visible(fn ($record) => $record->status === 'confirmed')
                        ->action(function ($record) {
                            $record->status = 'no_show';
                            $record->save();
                        })
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->extraAttributes([
                            'class' => 'border border-red-600 text-red-600 hover:bg-red-50 rounded-md px-4 py-10',
                        ]),
                    ]),
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
            'index' => Pages\ListAppointments::route('/'),
            'create' => Pages\CreateAppointment::route('/create'),
            'edit' => Pages\EditAppointment::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Appointments';
    }
}
