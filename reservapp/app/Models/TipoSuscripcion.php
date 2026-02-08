<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoSuscripcion extends Model
{
    /** @use HasFactory<\Database\Factories\TipoSuscripcionFactory> */
    use HasFactory;
    protected $table = 'tipo_suscripcion';
    protected $primaryKey = 'tipo_suscripcion';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
    ];
}
