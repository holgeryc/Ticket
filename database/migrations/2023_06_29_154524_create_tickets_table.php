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
            $table->unsignedBigInteger('Of')->nullable();
            $table->unsignedBigInteger('cod_usuario');
            $table->unsignedBigInteger('Encargado');
            $table->boolean('Asignado')->default(True);
            $table->text('Solucion');
            $table->timestamps();

            $table->foreign('Nro_ticket')->references('Cod_registro')->on('registros');
            $table->foreign('Encargado')->references('DNI')->on('users');
            $table->foreign('cod_usuario')->references('COd_registro')->on('registros');
            $table->foreign('Of')->references('codigo_oficina')
                ->on('oficinas');
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
