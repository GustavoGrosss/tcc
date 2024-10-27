<?php

namespace App\Models;

use Carbon\Carbon;
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
        'hora',
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

    public function dia_semana(): BelongsToMany
    {
        return $this->belongsToMany(DiaSemana::class, 'lembrete_dia_semana', 'id_lembrete', 'id_dia_semana');
    }
}
