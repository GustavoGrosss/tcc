<?php

namespace App\Filament\Resources\LembretesResource\Pages;

use App\Filament\Resources\LembretesResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateLembretes extends CreateRecord
{
    protected static string $resource = LembretesResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['id_cadastrante'] = Auth::user()->id;

        return $data;
    }
}
