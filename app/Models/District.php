<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    protected $fillable = ['region_id','district_name',];

    public function wards(){
        return $this->hasMany(Ward::class);

    }
    public function regions(){
        return $this->belongsTo(Region::class);
    }

    public function users(){
        return $this->hasMany(User::class);
    }
}
