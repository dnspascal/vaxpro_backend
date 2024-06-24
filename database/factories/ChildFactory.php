<?php

namespace Database\Factories;

use App\Models\Child;
use App\Models\Facility;
use App\Models\Ward;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChildFactory extends Factory
{
    protected $model = Child::class;

    public function definition()
    {
        $facility = Facility::all()->random();
        $ward = Ward::all()->random();
        $modifiedBy = User::all()->random();

        return [
            'card_no' => $this->faker->unique()->numerify('##########'),
            'firstname' => $this->faker->firstName,
            'middlename' => $this->faker->firstName,
            'surname' => $this->faker->lastName,
            'facility_id' => $facility->facility_reg_no,
            'ward_id' => $ward->id,
            'gender'=>'Male',
            'house_no' => $this->faker->optional()->buildingNumber,
            'date_of_birth' => $this->faker->date(),
            'modified_by' => $modifiedBy->id,
        ];
    }
}
