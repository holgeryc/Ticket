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
        Schema::create('registros',function(Blueprint $table){
            $table->unsignedBigInteger('Cod_registro')->primary();
            $table->unsignedBigInteger('Nro ticket');
            $table->unsignedBigInteger('usuario');
            $table->string('Descripcion_problema');
            $table->text('ruta_imagen')->nullable();
            $table->boolean('Asignado')->default(True);
            $table->date('Cod_registro');
            $table->timestamps();

            // $table->foreign('Nro ticket')->references('Nro ticket')->on('tickets');
            $table->foreign('usuario')->references('DNI')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registros');
    }
};
