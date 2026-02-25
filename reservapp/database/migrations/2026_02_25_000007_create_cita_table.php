<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cita', function (Blueprint $table) {
            $table->increments('id_cita');
            $table->unsignedInteger('id_coche');
            $table->unsignedInteger('id_usuario');
            $table->uuid('id_taller');
            $table->date('fecha');
            $table->string('tramo_horario', 20)->nullable();
            $table->text('motivo')->nullable();
            $table->integer('estado')->nullable();
            $table->decimal('total')->nullable();
            $table->decimal('iva')->nullable();
            $table->decimal('subtotal')->nullable();
            $table->text('detalles')->nullable();

            $table->foreign('id_coche')
                ->references('id_coche')->on('coche')
                ->onDelete('cascade');

            $table->foreign('id_usuario')
                ->references('id_usuario')->on('usuario')
                ->onDelete('cascade');

            $table->foreign('id_taller')
                ->references('id_taller')->on('taller')
                ->onDelete('cascade');

            $table->foreign(['fecha', 'id_taller'])
                ->references(['fecha', 'id_taller'])->on('dia');

            $table->index('fecha', 'idx_cita_fecha');
            $table->index('id_taller', 'idx_cita_taller');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cita');
    }
};
