<?php

namespace App\Helpers;

class VerificationCode
{
public static function generateCode($length = 6){
    $code = '';
    for ($i = 0; $i < 4; $i++) {
        $code .= random_int(0, 9); // Generate a random digit (0-9) and concatenate
    }
    return $code;
}
}
