<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoSuscripcion extends Model
{
    /** @use HasFactory<\Database\Factories\TipoSuscripcionFactory> */
    use HasFactory;
    protected $table = 'tipo_suscripcion';
    protected $primaryKey = 'id_estado';
    public $timestamps = false;

    protected $fillable = [
        'id_estado',
        'nombre',
        'precio',
        'descripcion',
    ];

    protected $casts = [
        'id_estado' => 'string',
    ];
}
