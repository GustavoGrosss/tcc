<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\TitularesSecundarios;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['password'] = Hash::make($data['password']);

        if (Auth::user()->tipo === 'A' ) {
            $data['tipo'] = 'T';
        }

        if (Auth::user()->tipo === 'T' ) {
            $data['tipo'] = 'S';
        }

        return $data;
    }


}
