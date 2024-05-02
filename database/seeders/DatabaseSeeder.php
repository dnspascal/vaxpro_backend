<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        Role::create([
            "role_id"=>"1000-1",
            'role'=>'IT_ADMIN',
            'account_type'=>'ministry'
        ]);
        User::factory()->create([
            'role_id' => "1000-1",
            'contacts'=>'+255745884099',
            'password' => '12345',
        ]);
    }
}
