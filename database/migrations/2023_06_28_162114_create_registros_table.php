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
            $table->unsignedBigInteger('Ticket');
            $table->unsignedBigInteger('Oficina');
            $table->unsignedBigInteger('Usuario');
            $table->string('Descripcion_problema');
            $table->text('Ruta_imagen')->nullable();
            $table->date('Fecha')->nullable();
            $table->timestamps();

            // $table->foreign('Nro ticket')->references('Nro ticket')->on('tickets');
            $table->foreign('Oficina')->references('codigo_oficina')
                ->on('oficinas');
            $table->foreign('Usuario')->references('DNI')->on('users');
            
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
