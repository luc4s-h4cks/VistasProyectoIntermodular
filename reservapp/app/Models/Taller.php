<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Taller extends Model
{
    /** @use HasFactory<\Database\Factories\TallerFactory> */
    use HasFactory;
    protected $table = 'taller';
    protected $primaryKey = 'id_taller';
    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'handle',
        'nombre',
        'img_perfil',
        'img_perfil_path',
        'img_sec',
        'img_sec_path',
        'telefono',
        'email',
        'tipo_vehiculo',
        'tipo_servicio',
        'descripcion',
        'info_contacto',
        'fecha_fin_suscripcion',
        'suscripcion',
    ];

    protected $casts = [
        'tipo_vehiculo' => 'array',
        'tipo_servicio' => 'array',
    ];


    public function citas()
    {
        return $this->hasMany(Cita::class, 'id_taller', 'id_taller');
    }

    public function dias(){
        return $this->hasMany(Dia::class, 'id_taller', 'id_taller');
    }

    public function usuario(){
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

}
