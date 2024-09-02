<?php

namespace App\Filament\Resources\LembretesResource\Pages;

use App\Filament\Resources\LembretesResource;
use App\Models\Lembretes;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class EditLembretes extends EditRecord
{
    protected static string $resource = LembretesResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function resolveRecord($key): \Illuminate\Database\Eloquent\Model
    {
        return Lembretes::query()
            ->select('lembretes.*')
            ->join('lembrete_usuario', 'lembrete_usuario.id_lembrete', 'lembretes.id')
            ->where('lembretes.id', $key)
            ->firstOrFail();
    }

}
