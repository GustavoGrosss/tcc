<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lembretes extends Model
{
    use HasFactory;

    protected $table = 'lembretes';

    protected $fillable = [
        'nome',
        'descricao',
        'dia_semana',
        'hora',
        'confirmado',
        'id_cadastrante',
    ];

    public function cadastrante(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_cadastrante');
    }
}
