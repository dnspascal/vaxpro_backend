<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    use HasFactory;

    protected $fillable = ['facility_reg_no','facility_name','contacts','ward_id','modified_by'];

    public function bookings(){
        return $this->hasMany(Booking::class);
    }
    public function child_vaccination_schedules(){
        return $this->hasMany(ChildVaccinationSchedule::class);

    }

    public function users(){
        return $this->hasMany(User::class);
    }

    public function health_workers(){
        return $this->hasMany(HealthWorker::class);
    }

    public function children(){
        return $this->hasMany(Child::class);
    }


}
