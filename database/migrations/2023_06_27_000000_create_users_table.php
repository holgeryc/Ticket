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
        Schema::create('users', function (Blueprint $table) {
            $table->unsignedBigInteger('DNI')->primary();
            $table->text('Nombres_y_Apellidos')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->enum('Tipo',['Centro_computo','Personal_Geredu','Administrador'])->nullable();
            $table->unsignedBigInteger('codigo_ug')->nullable();
            $table->unsignedBigInteger('codigo_of')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('codigo_of')->references('codigo_oficina')
                ->on('oficinas');
            $table->foreign('codigo_ug')->references('ug')
                ->on('ugeles');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
