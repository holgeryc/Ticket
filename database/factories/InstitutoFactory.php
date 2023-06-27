<?php

namespace Database\Factories;

use App\Models\Oficina;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OficinaFactory extends Factory
{
    protected $model = Oficina::class;

    public function definition()
    {
        return [
			'codigo_oficina' => $this->faker->name,
			'Nombre' => $this->faker->name,
			'unidad' => $this->faker->name,
        ];
    }
}
