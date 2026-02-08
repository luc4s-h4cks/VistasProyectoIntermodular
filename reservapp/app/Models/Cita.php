<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    /** @use HasFactory<\Database\Factories\CitaFactory> */
    use HasFactory;

    protected $table = 'cita';
    protected $primaryKey = 'id_cita';
    public $timestamps = false;

    protected $fillable = [
        'id_coche',
        'id_taller',
        'fecha',
        'tramo_horario',
        'motivo',
        'estado',
    ];

    public function coche(){
        return $this->belongsTo(Coche::class, 'id_coche', 'id_coche');
    }

    public function taller(){
        return $this->belongsTo(Taller::class, 'id_taller', 'id_taller');
    }

    public function dia(){
        return $this->belongsTo(Dia::class, 'fecha', 'fecha');
    }
}
