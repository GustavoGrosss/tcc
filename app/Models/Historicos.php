<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Historicos extends Model
{
    use HasFactory;

    protected $table = 'historicos';

    protected $fillable = [
        'dia_semana',
        'confirmado',
        'data_notificacao',
        'data_confirmacao',
        'id_notificacao',
        'id_usuario',
    ];

}
