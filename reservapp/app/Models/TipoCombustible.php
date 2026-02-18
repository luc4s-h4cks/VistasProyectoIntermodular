<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoCombustible extends Model
{
    /** @use HasFactory<\Database\Factories\TipoCombustibleFactory> */
    use HasFactory;
    protected $table = 'tipo_propulsion';
    protected $primaryKey = 'tipo_combustible';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
    ];

    public function coches(){
        return $this->hasMany(Coche::class, 'tipo_conbustible', 'tipo_combustible');
    }
}
