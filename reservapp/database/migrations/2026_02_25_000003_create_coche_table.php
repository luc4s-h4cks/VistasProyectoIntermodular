<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coche', function (Blueprint $table) {
            $table->increments('id_coche');
            $table->unsignedInteger('id_usuario');
            $table->string('matricula', 16)->unique();
            $table->string('n_bastidor', 32)->nullable();
            $table->string('marca', 32)->nullable();
            $table->string('modelo', 32)->nullable();
            $table->unsignedInteger('tipo_combustible');

            $table->foreign('id_usuario')
                ->references('id_usuario')->on('usuario')
                ->onDelete('cascade');

            $table->foreign('tipo_combustible')
                ->references('tipo_combustible')->on('tipo_propulsion');

            $table->index('id_usuario', 'idx_coche_usuario');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coche');
    }
};
