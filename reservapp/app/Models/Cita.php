<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    /** @use HasFactory<\Database\Factories\CitaFactory> */
    use HasFactory;
    const ESTADO_RECHAZADO_POR_CLIENTE = -2;
    const ESTADO_RECHAZADO_POR_TALLER = -1;
    const ESTADO_SOLICITADO = 0;
    const ESTADO_ACEPTADO = 1;
    const ESTADO_TEMINADO = 2;
    const ESTADO_ESPERANDO_PAGO = 3;
    const ESTADO_PAGADA = 4;

    const ESTADO_FECHA_PROPUESTA = 10;
    const ESTADO_FECHA_ACEPTADA_CLIENTE = 11;

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

    public function coche()
    {
        return $this->belongsTo(Coche::class, 'id_coche', 'id_coche');
    }

    public function taller()
    {
        return $this->belongsTo(Taller::class, 'id_taller', 'id_taller');
    }

    public function dia()
    {
        return $this->belongsTo(Dia::class, 'fecha', 'fecha');
    }
}
