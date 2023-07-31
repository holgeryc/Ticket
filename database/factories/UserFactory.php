<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
			'DNI' => $this->faker->name,
			'Nombres_y_Apellidos' => $this->faker->name,
			'email' => $this->faker->name,
			'Tipo' => $this->faker->name,
			'codigo_of' => $this->faker->name,
            'codigo_ug' => $this->faker->name,
			'Activado' => $this->faker->name,
        ];
    }
}
