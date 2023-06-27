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
			'Fecha' => $this->faker->name,
			'N°_Voucher' => $this->faker->name,
			'N°_Cheque' => $this->faker->name,
			'C_P' => $this->faker->name,
			'DNI' => $this->faker->name,
			'Detalle' => $this->faker->name,
			'Entrada' => $this->faker->name,
			'Salida' => $this->faker->name,
			'Saldo' => $this->faker->name,
			'codigo_oficina_Oficina' => $this->faker->name,
			'Activado' => $this->faker->name,
        ];
    }
}
