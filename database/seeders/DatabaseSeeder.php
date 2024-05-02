<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\Region;
use App\Models\Role;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Ward;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        

        $json_region = file_get_contents(database_path('json/region.json'));
        $data_region = json_decode($json_region, true);

        $json_district = file_get_contents(database_path('json/district.json'));
        $data_district = json_decode($json_district, true);

        $json_ward = file_get_contents(database_path('json/ward.json'));
        $data_ward = json_decode($json_ward, true);

        foreach ($data_region["features"] as $region) {

            Region::create(["region_name"=>$region["properties"]['region'] ]);
        }

        foreach ($data_district["features"] as $district) {
            $region = Region::where('region_name',$district['properties']['region'])->first();
            
            if($region){

                District::create(["region_id"=>$region->id,"district_name"=>$district["properties"]["District"] ]);
            }
        }

        foreach ($data_ward["features"] as $ward) {
            $district = District::where('district_name',$ward['properties']['District'])->first();
            
            if($district){

                
                Ward::create(["ward_name"=>$ward["properties"]['Ward'],"district_id"=>$district->id ]);
            }
         }

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
