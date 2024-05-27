<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class Certificates extends Model
{
protected  $fillable = [
    "certificate",
    "hpv_certificate",
    "child_id"
];


public function child(){
    return $this->belongsTo(Child::class, 'child_id');
}
}
