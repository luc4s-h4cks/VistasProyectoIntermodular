<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    /** @use HasFactory<\Database\Factories\UsuarioFactory> */
    use HasFactory;
    protected $table = 'usuario';
    protected $primaryKey = 'id_usuario';
    public $timestamps = false;

    protected $fillable = [
        'nombre_usuario',
        'tipo',
        'pass',
        'email',
        'nombre',
        'apellidos',
        'telefono',
        'fecha_nacimiento',
        'fecha_creacion_cuenta',
        'img_perfil'
    ];

    protected $hidden = [
        'pass',
    ];

    public function coches(){
        return $this->hasMany(Coche::class, 'id_usuario', 'id_usuario');
    }

    public function usuario(){
        return $this->hasOne(Taller::class, 'id_usuario', 'id_usuario');
    }

}
