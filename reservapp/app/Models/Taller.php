<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Taller extends Model
{
    use HasFactory;

    protected $table = 'taller';
    protected $primaryKey = 'id_taller';

    //Esto indica que el PK es string y no autoincremental
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'id_taller',
        'id_usuario',
        'handle',
        'nombre',
        'img_perfil',
        'img_sec',
        'telefono',
        'email',
        'tipo_vehiculo',
        'tipo_servicio',
        'descripcion',
        'info_contacto',
        'fecha_fin_suscripcion',
        'suscripcion',
        'ubicacion'
    ];

    protected $casts = [
        'tipo_vehiculo' => 'array',
        'tipo_servicio' => 'array',
    ];

    // 🛠 Generar UUID automáticamente al crear
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->id_taller) {
                $model->id_taller = (string) Str::uuid();
            }
        });
    }

    public function citas()
    {
        return $this->hasMany(Cita::class, 'id_taller', 'id_taller');
    }

    public function dias()
    {
        return $this->hasMany(Dia::class, 'id_taller', 'id_taller');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    public function tipoSuscripcion()
    {
        return $this->belongsTo(TipoSuscripcion::class, 'suscripcion', 'id_estado');
    }

}
