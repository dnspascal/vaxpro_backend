<?php

namespace App\Helpers;
class GeneratePasswordHelper {
    public static function generatePassword() {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $password='';

        for( $i = 0; $i < 8; $i++ ){
            $password .= $characters[ rand(0, strlen( $characters ) -1) ];
        }
        return $password;
    }
}