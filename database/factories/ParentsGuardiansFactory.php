<?php

namespace Database\Factories;

use App\Models\ParentsGuardians;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ParentsGuardiansFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = ParentsGuardians::class;
    public function definition(): array
    {
        return [
            'nida_id' => $this->faker->unique()->numerify('####################'),
            'firstname' => $this->faker->firstName,
            'middlename' => $this->faker->firstName,
            'lastname' => $this->faker->lastName,
            'user_id' => User::where("role_id", 13)->get()->random()->id,
        ];
    }
}
