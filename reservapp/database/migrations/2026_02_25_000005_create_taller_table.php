<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('taller', function (Blueprint $table) {
            $table->uuid('id_taller')->primary()->default(DB::raw('UUID()'));
            $table->unsignedInteger('id_usuario');
            $table->string('handle', 255)->unique()->nullable();
            $table->string('nombre', 255)->nullable();
            $table->string('img_perfil', 255)->nullable();
            $table->string('img_perfil_path', 255)->nullable();
            $table->string('img_sec', 255)->nullable();
            $table->string('img_sec_path', 255)->nullable();
            $table->string('telefono', 12)->nullable();
            $table->string('email', 50)->nullable();
            $table->jsonb('tipo_vehiculo')->nullable();
            $table->jsonb('tipo_servicio')->nullable();
            $table->string('descripcion', 255)->nullable();
            $table->string('info_contacto', 255)->nullable();
            $table->date('fecha_fin_suscripcion')->nullable();
            $table->uuid('suscripcion')->nullable();
            $table->string('ubicacion')->nullable();

            $table->foreign('id_usuario')
                ->references('id_usuario')->on('usuario')
                ->onDelete('cascade');

            $table->foreign('suscripcion')
                ->references('id_estado')->on('tipo_suscripcion');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('taller');
    }
};
