<?php

namespace Database\Seeders;

use App\Models\Child;
use App\Models\District;
use App\Models\Facility;
use App\Models\ParentsGuardians;
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

        $facilities = file_get_contents(database_path('json/facilities.json'));
        $data_facilities = json_decode($facilities, true);


        $json_region = file_get_contents(database_path('json/region.json'));
        $data_region = json_decode($json_region, true);

        $json_district = file_get_contents(database_path('json/district.json'));
        $data_district = json_decode($json_district, true);

        $json_ward = file_get_contents(database_path('json/ward.json'));
        $data_ward = json_decode($json_ward, true);


        $json_roles = file_get_contents(database_path('json/roles.json'));
        $roles = json_decode($json_roles, true);


        foreach ($data_region["features"] as $region) {

            Region::create(["region_name" => $region["properties"]['region']]);
        }

        foreach ($data_district["features"] as $district) {
            $region = Region::where('region_name', $district['properties']['region'])->first();

            if ($region) {

                District::create(["region_id" => $region->id, "district_name" => $district["properties"]["District"]]);
            }
        }

        foreach ($data_ward["features"] as $ward) {
            $district = District::where('district_name', $ward['properties']['District'])->first();

            if ($district) {


                Ward::create(["ward_name" => $ward["properties"]['Ward'], "district_id" => $district->id]);
            }
        }


         foreach ($data_facilities as $facility) {

            Facility::create(["facility_reg_no"=>$facility["facility_reg_no"],"facility_name"=>$facility["facility_name"],"contacts"=>$facility["contacts"],"ward_id"=>$facility["ward_id"] ]);
        }


        foreach ($roles["roles"] as $role) {
            Role::create(["role" => $role["name"], "account_type" => $role["account_type"]]);
        }


        User::create([
            'uid' => "1000-1-1",
            "role_id" => 1,
            'contacts' => '+255745884099',
            'password' => '12345',
        ]);

        User::factory(100)->create();
        ParentsGuardians::factory(100)->create();
        Child::factory(120)->create();

    }
}
