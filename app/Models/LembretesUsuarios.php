<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LembretesUsuarios extends Model
{
    use HasFactory;

    protected $table = 'lembrete_usuario';

    protected $fillable = [
        'id_destinatario',
        'id_lembrete',
    ];
}
