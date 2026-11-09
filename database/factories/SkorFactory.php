<?php

namespace Database\Factories;

use App\Models\Skor;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Skor> */
class SkorFactory extends Factory
{
    protected $model = Skor::class;

    public function definition(): array
    {
        return [
            'inisial'      => strtoupper(fake()->lexify('???')),
            'persen'       => fake()->numberBetween(0, 100),
            'ketelitian'   => fake()->numberBetween(0, 100),
            'kecepatan'    => fake()->numberBetween(0, 100),
            'percaya_diri' => fake()->numberBetween(0, 200),
            'waktu'        => fake()->numberBetween(30, 600),
            'kategori'     => fake()->randomElement(array_keys(config('kebodohan.kategori'))),
        ];
    }
}
