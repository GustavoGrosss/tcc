<?php

namespace App\Filament\Resources\LembretesResource\Pages;

use App\Filament\Resources\LembretesResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLembretes extends ListRecords
{
    protected static string $resource = LembretesResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
