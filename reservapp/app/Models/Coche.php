<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coche extends Model
{
    /** @use HasFactory<\Database\Factories\CocheFactory> */
    use HasFactory;

    protected $table = 'coche';
    protected $primaryKey = 'id_coche';
    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'matricula',
        'n_bastidor',
        'marca',
        'modelo',
        'tipo_combustible',
    ];

    public function citas(){
        return $this->hasMany(Cita::class, 'id_coche', 'id_coche');
    }

    public function usuario(){
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    public function tipoCombustible(){
        return $this->belongsTo(TipoCombustible::class, 'tipo_combustible', 'tipo_combustible');
    }


}
