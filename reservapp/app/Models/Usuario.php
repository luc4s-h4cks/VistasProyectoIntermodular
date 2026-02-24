<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\ResetPasswordNotification;
use App\Notifications\VerifyEmailNotification;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable implements MustVerifyEmail
{
    public const USUARIO = 0;
    public const MECANICO = 1;
    public const ADMIN = 2;

    use HasFactory, Notifiable, TwoFactorAuthenticatable, HasApiTokens;

    protected $table = 'usuario';
    protected $primaryKey = 'id_usuario';
    public $timestamps = false;

    protected $fillable = [
        'nombre_usuario',
        'email',
        'pass',
        'tipo',
        'nombre',
        'apellidos',
        'telefono',
        'fecha_nacimiento',
        'fecha_creacion_cuenta',
        'img_perfil',
    ];

    protected $hidden = [
        'pass',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    protected function casts(): array
    {
        return [
            'fecha_nacimiento' => 'date',
            'fecha_creacion_cuenta' => 'datetime',
        ];
    }


    /**
     * 🔑 Decirle a Laravel qué campo es la contraseña
     */
    public function getAuthPassword()
    {
        return $this->pass;
    }

    /* =======================
       RELACIONES
       ======================= */

    // Usuario → Coches (1:N)
    public function coches()
    {
        return $this->hasMany(Coche::class, 'id_usuario', 'id_usuario');
    }

    // Usuario → Taller (1:1)
    public function taller()
    {
        return $this->hasOne(Taller::class, 'id_usuario', 'id_usuario');
    }

    // Usuario → Citas (a través de coches)
    public function citas()
    {
        return $this->hasManyThrough(
            Cita::class,
            Coche::class,
            'id_usuario', // FK en coche
            'id_coche',   // FK en cita
            'id_usuario', // PK usuario
            'id_coche'    // PK coche
        );
    }

    /* 🔹 ACCESSORS PARA LA UI */

    public function getNameAttribute(): string
    {
        return trim("{$this->nombre} {$this->apellidos}");
    }

    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmailNotification);
    }

}
