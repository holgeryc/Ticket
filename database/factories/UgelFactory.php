<?php

namespace Database\Factories;

use App\Models\Ugel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UgelFactory extends Factory
{
    protected $model = Ugel::class;

    public function definition()
    {
        return [
			'ug' => $this->faker->name,
			'nombre_ugel' => $this->faker->name,
        ];
    }
}
