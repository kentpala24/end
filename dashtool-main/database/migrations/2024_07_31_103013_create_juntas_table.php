<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('juntas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_servicio');
            $table->foreign('id_servicio')->references('id')->on('servicios');
            $table->string('inspeccion');
            $table->string('diametro');
            $table->string('codigo');
            $table->string('resultado');
            $table->string('Comentarios');
            $table->date('fecha_reporte');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('juntas');
    }
};
