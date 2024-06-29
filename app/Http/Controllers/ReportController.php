<?php

namespace App\Http\Controllers;

use App\Models\Child;
use App\Models\ChildVaccinationSchedule;
use App\Models\Vaccination;
use Illuminate\Http\Request;

class ReportController extends Controller
{

    public function reportData(Request $request)
    {

        $regionId = $request->region;
        $districtId = $request->district;
        $facilityId = $request->facility;
        $month = $request->month;
        $year = $request->month;

        $data = [];

        $registered_children = Child::query()
            ->when($regionId, function ($query) use ($regionId) {
                $query->whereHas('ward.district.region', function ($query) use ($regionId) {
                    $query->where('id', $regionId);
                });
            })
            ->when($districtId, function ($query) use ($districtId) {
                $query->whereHas('ward.district', function ($query) use ($districtId) {
                    $query->where('id', $districtId);
                });
            })
            ->when($year, function ($query) use ($year, $month) {
                $query->whereYear('created_at', $year)->when($month, function ($query) use ($month) {
                    $query->whereMonth('created_at', $month);
                });
            })->when($facilityId, function ($query) use ($facilityId) {
                $query->whereYear('facility_id', $facilityId);
                });
            

        $vaccinated_children = Child::query()
            ->when($regionId, function ($query) use ($regionId) {
                $query->whereHas('ward.district.region', function ($query) use ($regionId) {
                    $query->where('id', $regionId);
                });
            })
            ->when($districtId, function ($query) use ($districtId) {
                $query->whereHas('ward.district', function ($query) use ($districtId) {
                    $query->where('id', $districtId);
                });
            })
            ->when($facilityId, function ($query) use ($facilityId) {
                $query->whereYear('facility_id', $facilityId);
                })
            ->when($year, function ($query) use ($year, $month) {
                $query->whereYear('created_at', $year)->when($month, function ($query) use ($month) {
                    $query->whereMonth('created_at', $month);
                });
            })->whereHas('vaccinations');

        // registered children
        // $totalChildren = (clone $registered_children)->get();
        $maleChildren = (clone $registered_children)->where('gender', 'Male')->count();
        $femaleChildren = (clone $registered_children)->where('gender', 'female')->count();

        

        // Retrieve all vaccinations
        $vaccinations = Vaccination::all();

        // Initialize reports array
        $reports = [];

        // Retrieve registered children statistics
        // $maleChildren = Child::where('gender', 'Male')->count();
        // $femaleChildren = Child::where('gender', 'Female')->count();
        $totalChildren = $maleChildren + $femaleChildren;

        // Add registered children statistics to reports
        $reports[] = [
            "No" => 1,
            "Description" => "Number of registered children",
            "Male" => $maleChildren,
            "Female" => $femaleChildren,
            "Total" => $totalChildren,
            "Dose" => ""
        ];

        // Add header for type of vaccination by age
        $reports[] = [
            "No" => 2,
            "Description" => "Type of vaccination by age",
            "Male" => "",
            "Female" => "",
            "Total" => "",
            "Dose" => ""
        ];

        $counter = 3; // Initialize counter for vaccines
        $reportArray = [" < 1 within facility", " + 1 within facility", " < 1 outside facility", " + 1 outside facility"];

        // Loop through each vaccination
        foreach ($vaccinations as $vaccination) {
            $vaccinationId = $vaccination->id;
            $vaccinationAbbrev = $vaccination->abbrev;
            $frequency = $vaccination->frequency;

            // Loop through each dose frequency
            for ($i = 1; $i <= $frequency; $i++) {
                
                foreach ($reportArray as $key => $report) {
                   

                    $vaccinated_male = (clone $vaccinated_children)->whereHas('child_vaccination_schedules', function ($query) use ($key) {

                        switch ($key) {
                            case 0:
                                $query->whereRaw('DATEDIFF(child_vaccination_schedules.vaccination_date, children.date_of_birth) < 365');
                                $query->where('within_facility', 1);
                                break;
                            case 1:
                                $query->whereRaw('DATEDIFF(child_vaccination_schedules.vaccination_date, children.date_of_birth) > 365');
                                $query->where('within_facility', 1);
                                break;
                            case 2:
                                $query->whereRaw('DATEDIFF(child_vaccination_schedules.vaccination_date, children.date_of_birth) < 365');
                                $query->where('within_facility', 0);
                                break;
                            case 3:
                                $query->whereRaw('DATEDIFF(child_vaccination_schedules.vaccination_date, children.date_of_birth) < 365');
                                $query->where('within_facility', 0);
                                break;
                        }
                    })->where('gender', 'Male')->count();


                    $vaccinated_female = (clone $vaccinated_children)->whereHas('child_vaccination_schedules', function ($query) use ($key) {

                        switch ($key) {
                            case 0:
                                $query->whereRaw('DATEDIFF(child_vaccination_schedules.vaccination_date, children.date_of_birth) < 365');
                                $query->where('within_facility', 1);
                                break;
                            case 1:
                                $query->whereRaw('DATEDIFF(child_vaccination_schedules.vaccination_date, children.date_of_birth) > 365');
                                $query->where('within_facility', 1);
                                break;
                            case 2:
                                $query->whereRaw('DATEDIFF(child_vaccination_schedules.vaccination_date, children.date_of_birth) < 365');
                                $query->where('within_facility', 0);
                                break;
                            case 3:
                                $query->whereRaw('DATEDIFF(child_vaccination_schedules.vaccination_date, children.date_of_birth) < 365');
                                $query->where('within_facility', 0);
                                break;
                        }
                    })->where('gender', 'Female')->count();


                    $vaccinated_total = $vaccinated_male + $vaccinated_female;

                    // Create report entry
                    $reports[] = [
                        "No" => "{$counter}",
                        "Description" => $vaccinationAbbrev . " " . $report,
                        "Male" => $vaccinated_male,
                        "Female" => $vaccinated_female,
                        "Total" => $vaccinated_total,
                        "Dose" => "Dose {$i}"
                    ];
                    $counter++;
                }
            }

        }

        return response()->json($reports, 200);
    }
}
