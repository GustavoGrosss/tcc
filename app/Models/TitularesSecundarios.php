<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TitularesSecundarios extends Model
{
    use HasFactory;

    protected $table = 'titulares_secundarios';

    protected $fillable = [
        'nome',
        'descricao',
        'dia_semana',
        'hora',
        'confirmado',
        'id_cadastrante',
    ];

}
