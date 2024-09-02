<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\TitularesSecundarios;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function created($record): void
    {
//        dd(123);
        $currentUser = Auth::user();

//        if ($currentUser->tipo === 'T') {
            TitularesSecundarios::create([
                'id_titular'    => $currentUser->id,
                'id_secundario' => $record->id,
            ]);
//        }
    }

    protected function afterSave(): void
    {
        $currentUser = Auth::user();

//        if ($currentUser->tipo === 'T') {
            // Criar a relação após o usuário ter sido salvo no banco de dados
            TitularesSecundarios::create([
                'id_titular' => $currentUser->id,
                'id_secundario' => $this->record->id,
            ]);
//        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
