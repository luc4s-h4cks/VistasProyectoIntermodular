<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tipo_propulsion', function (Blueprint $table) {
            $table->increments('tipo_combustible');
            $table->string('nombre', 32);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tipo_propulsion');
    }
};
