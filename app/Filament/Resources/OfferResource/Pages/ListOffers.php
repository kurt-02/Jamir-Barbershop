<?php

namespace App\Filament\Resources\OfferResource\Pages;

use App\Filament\Resources\OfferResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Models\Offer;

class ListOffers extends ListRecords
{
    protected static string $resource = OfferResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->visible(fn () => Offer::count() < 2) // ide button if 2 offers exist
                ->disabled(fn () => Offer::count() >= 2) // optional: disable instead of hide
                ->tooltip('You can only add up to 2 offers.'),
        ];
    }
}
