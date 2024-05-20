<?php

namespace App\Helpers;

use App\Models\User;
use App\Models\Region;

class GenerateRoleIdHelper
{
    public static function generateRoleId($account_type, $region_id, $district_id, $ward_id)
    {

        $uids = ($account_type === "ministry")
            ? User::where('uid', 'LIKE', '1000%')->pluck('uid')->toArray()
            : (($account_type === "regional")
                ? User::where('uid', 'LIKE', "2000-{$region_id}%")->pluck('uid')->toArray()
                : (($account_type === "district")
                    ? User::where('uid', 'LIKE', "3000-{$district_id}%")->pluck('uid')->toArray()
                    : (($account_type === "branch_manager")
                        ? User::where('uid', 'LIKE', "4000-{$ward_id}%")->pluck('uid')->toArray()
                        : (($account_type === "health_worker")

                            ? User::where('uid', 'LIKE', "5000-{$ward_id}%")->pluck('uid')->toArray()
                            : (($account_type === "community_health_worker")
                                ? User::where("uid", "LIKE", "6000-{$ward_id}%")->pluck('uid')->toArray()
                                : (($account_type === "parent")
                                    ? User::where("uid", "LIKE", "7000-{$ward_id}%")->pluck("uid")->toArray()
                                    :
                                    [])))

                    )
                ));


        $suffixes = [];
        foreach ($uids as $uid) {
            $suffixes[] = (int) explode('-', $uid)[2];
        }
        $maxSuffix = $suffixes ? max($suffixes) + 1 : 1;
        

        ($account_type === "ministry") ?  $uid = '1000-' . "1" . "-" . $maxSuffix
            : (($account_type === "regional") ? $uid = '2000-' . $region_id . "-" . $maxSuffix
                : (($account_type === 'district') ? $uid = '3000-' . $district_id . "-" . $maxSuffix
                    : (($account_type === "branch_manager") ?  $uid = '4000-' ."1". "-" . $maxSuffix
                        : (($account_type === "health_worker") ? $uid = "5000-" . "2" . "-" . $maxSuffix
                            : (($account_type === "community_health_worker") ? $uid = "6000-" . $ward_id . "-" . $maxSuffix
                                : (($account_type === "parent") ? $uid = "7000-" . $ward_id . "-" . $maxSuffix
                                    : null)

                            )))


                )
            );

        return $uid;
    }
}
