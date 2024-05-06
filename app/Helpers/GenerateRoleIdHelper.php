<?php

namespace App\Helpers;

use App\Models\Role;

class GenerateRoleIdHelper
{
    public static function generateRoleId($account_type)
    {
        $roleIds = ($account_type === "ministry")
            ? Role::where('role_id', 'LIKE', '1000%')->pluck('role_id')->toArray()
            : (($account_type === "regional")
                ? Role::where('role_id', 'LIKE', '2000%')->pluck('role_id')->toArray()
                : (($account_type === "district")
                    ? Role::where('role_id', 'LIKE', '3000%')->pluck('role_id')->toArray()
                    : (($account_type === "branch_manager")
                        ? Role::where('role_id', 'LIKE', '4000%')->pluck('role_id')->toArray()
                        : (($account_type === "health_worker")
                            ? Role::where('role_id', 'LIKE', '5000%')->pluck('role_id')->toArray()
                            : (($account_type === "parent")
                                ? Role::where('role_id', 'LIKE', '6000%')->pluck('role_id')->toArray()
                                : [])
                        )
                    )
                ));

        $suffixes = [];
        foreach ($roleIds as $roleId) {
            $suffixes[] = (int) explode('-', $roleId)[1];
        }
        $maxSuffix = $suffixes ? max($suffixes) + 1 : 0;

        ($account_type === "ministry") ?  $roleId = '1000-' . $maxSuffix
            : (($account_type === "regional") ? $roleId = '2000-' . $maxSuffix
                : (($account_type === 'district') ? $roleId = '3000-' . $maxSuffix
                    : (($account_type === "branch_manager") ?  $roleId = '4000-' . $maxSuffix
                        : (($account_type === "health_worker") ? $roleId = "5000-" . $maxSuffix
                            : (($account_type === "parent") ? $roleId = "6000-" . $maxSuffix

                                : 0)))

                )
            );

        return $roleId;
    }
}
