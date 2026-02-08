<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoCombustible extends Model
{
    /** @use HasFactory<\Database\Factories\TipoCombustibleFactory> */
    use HasFactory;
    protected $table = 'tipo_propulsion';
    protected $primaryKey = 'tipo_propulsion';
    public $timestamps = false;

    protected $filleable = [
        'nombre',
    ];
}
