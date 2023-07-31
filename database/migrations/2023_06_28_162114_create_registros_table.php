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
            $table->unsignedBigInteger('cod_ugel');
            $table->unsignedBigInteger('Oficina');
            $table->unsignedBigInteger('Usuario');
            $table->string('Descripcion_problema');
            $table->unsignedBigInteger('Encargado')->nullable();
            $table->enum('Estado',['Recibido','Proceso','Terminado'])->nullable();
            $table->text('Solucion')->nullable();
            $table->text('Ruta_imagen')->nullable();
            $table->date('Fecha_Inicio');
            $table->date('Fecha_Final')->nullable();
            $table->timestamps();

            // $table->foreign('Nro ticket')->references('Nro ticket')->on('tickets');
            $table->foreign('Oficina')->references('codigo_oficina')
                ->on('oficinas');
            $table->foreign('Usuario')->references('DNI')->on('users');
            $table->foreign('Encargado')->references('DNI')->on('users');
            $table->foreign('cod_ugel')->references('ug')
                ->on('ugeles');
            
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
