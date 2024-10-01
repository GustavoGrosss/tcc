<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notificacao extends Model
{
    use HasFactory;

    protected $table = 'notificacao';

    protected $fillable = [
        'data_disparo',
        'confirmado',
        'id_lembrete',
        'id_usuario',
    ];

    public function lembrete(): BelongsTo
    {
        return $this->belongsTo(Lembretes::class, 'id_lembrete');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }
}
