<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dia extends Model
{

    const ESTADO_LIBRE = 0;
    const ESTADO_OCUPADO = 1;

    /** @use HasFactory<\Database\Factories\DiaFactory> */
    use HasFactory;
    protected $table = 'dia';
    protected $primaryKey = 'fecha';
    public $timestamps = false;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'fecha',
        'estado',
        'id_taller',
    ];

    protected $casts = [
        'fecha' => 'string',
    ];


    public function citas()
    {
        return $this->hasMany(Cita::class, 'fecha', 'fecha');
    }

    public function taller()
    {
        return $this->belongsTo(Taller::class, 'id_taller', 'id_taller');
    }

}
