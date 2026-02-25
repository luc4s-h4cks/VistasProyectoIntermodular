<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dia', function (Blueprint $table) {
            $table->date('fecha');
            $table->integer('estado');
            $table->uuid('id_taller');

            $table->primary(['fecha', 'id_taller']);

            $table->foreign('id_taller')
                ->references('id_taller')->on('taller')
                ->onDelete('cascade');

            $table->index('id_taller', 'idx_dia_taller');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dia');
    }
};
