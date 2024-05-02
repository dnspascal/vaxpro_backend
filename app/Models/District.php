<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    protected $fillable = ['region_id','district_name',];

    public function ward(){
        return $this->hasMany(Ward::class);

    }
    public function region(){
        return $this->belongsTo(Region::class);
    }

    public function user(){
        return $this->hasMany(User::class);
    }
}
