<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usuario', function (Blueprint $table) {
            $table->increments('id_usuario');
            $table->integer('tipo');
            $table->string('nombre_usuario', 32);
            $table->string('pass', 120);
            $table->string('email', 64)->unique();
            $table->string('nombre', 32)->nullable();
            $table->string('apellidos', 32)->nullable();
            $table->string('telefono', 12)->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->date('fecha_creacion_cuenta')->default(DB::raw('CURRENT_DATE'));
            $table->string('img_perfil', 150)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('remember_token')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuario');
        Schema::dropIfExists('sessions');
    }
};
