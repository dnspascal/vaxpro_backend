<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Ward;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Helpers\GenerateRoleIdHelper;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {

        $ward = Ward::all()->random();


        $uid = GenerateRoleIdHelper::generateRoleId("parent",  null, null, $ward->id);
        $phoneNumber = '+255' . $this->faker->randomElement(['7', '6']) . $this->faker->numerify('########');
        return [
            'uid' => $this->faker->unique()->numerify('######'),
            'role_id' => 10,
            'password' => Hash::make("12345"), // or use a hash function
            'ward_id' => $ward->id,
            'contacts' => $phoneNumber,
        ];
    }
}
