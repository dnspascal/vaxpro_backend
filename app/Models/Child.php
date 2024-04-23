<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Child extends Model
{
    use HasFactory;
    protected $fillable = ['firstname','middlename','surname','parent_id','facility_id','ward_id','address_name','house_no','date_of_birth','modified_by'];

    public function bookings(){
        return $this->hasMany(Booking::class);
    }


    public function vaccinations(){
        return $this->hasMany(Vaccination::class);
    }

    public function users(){
        return $this->belongsTo(User::class);
    }

    public function facilities(){
        return $this->belongsTo(Facility::class);
    }

    public function wards(){
        return $this->belongsTo(Ward::class);
    }
    
    public function health_workers(){
        return $this->belongsTo(HealthWorker::class);
    }

}
