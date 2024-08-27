<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    public function destinatarios(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'lembrete_usuario', 'id_lembrete', 'id_destinatario');
    }
}
