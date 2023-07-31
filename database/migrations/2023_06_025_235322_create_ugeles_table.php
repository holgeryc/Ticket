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
        Schema::create('ugeles', function (Blueprint $table) {
            $table->unsignedBigInteger('ug')->primary();
            $table->string('nombre_ugel');
            $table->unsignedBigInteger('nombre_oficina');
            $table->timestamps();

            $table->foreign('nombre_oficina')->references('codigo_oficina')
                ->on('oficinas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ugeles');
    }
};
