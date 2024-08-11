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
        Schema::create('servicios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_usuario');
            $table->foreign('id_usuario')->references('id')->on('users');
            $table->unsignedBigInteger('id_cliente');
            $table->foreign('id_cliente')->references('id')->on('clientes');
            $table->string('ois');
            $table->string('proyecto');
            $table->string('inspeccion');
            $table->string('cant_elem')->nullable();
            $table->string('desc_cant_elem');
            $table->tinyInteger('status')->default(1)->comment('1 confirmada, 2 en Proceso, 3 Revision,4  Concluida');
            $table->date('fecha_inicio')->nullable();
            $table->string('horario');
            $table->string('observacion');
            $table->timestamps();
        });

        Schema::create('personal_servicios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_usuario');
            $table->foreign('id_usuario')->references('id')->on('users');
            $table->unsignedBigInteger('id_servicio');
            $table->foreign('id_servicio')->references('id')->on('servicios');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servicios');
        Schema::dropIfExists('personal_servicios');
    }
};
