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
        Schema::create('tickets', function (Blueprint $table) {
            $table->unsignedBigInteger('Nro_ticket')->primary();
            $table->unsignedBigInteger('Registro');
            $table->unsignedBigInteger('Encargado');
            $table->text('Solucion');
            $table->timestamps();

            $table->foreign('Registro')->references('Cod_registro')->on('registros');
            $table->foreign('Encargado')->references('DNI')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
