<?php

namespace Database\Factories;

use App\Models\Registro;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class RegistroFactory extends Factory
{
    protected $model = Registro::class;

    public function definition()
    {
        return [
			'Cod_registro' => $this->faker->name,
			'Nro_ticket' => $this->faker->name,
			'usuario' => $this->faker->name,
			'C_P' => $this->faker->name,
			'DNI' => $this->faker->name,
			'Descripcion_problema' => $this->faker->name,
			'ruta_imagen' => $this->faker->name,
			'Asignado' => $this->faker->name,
			'Saldo' => $this->faker->name,
			'codigo_oficina_Oficina' => $this->faker->name,
			'Activado' => $this->faker->name,
        ];
    }
}
