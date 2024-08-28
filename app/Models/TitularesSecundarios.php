<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TitularesSecundarios extends Model
{
    use HasFactory;

    protected $table = 'titulares_secundarios';

    protected $fillable = [
        'id_titular',
        'id_secundario',
    ];

}
