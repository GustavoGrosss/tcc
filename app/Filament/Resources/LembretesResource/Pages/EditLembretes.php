<?php

namespace App\Filament\Resources\LembretesResource\Pages;

use App\Filament\Resources\LembretesResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLembretes extends EditRecord
{
    protected static string $resource = LembretesResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
