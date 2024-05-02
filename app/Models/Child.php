<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Child extends Model
{
    use HasFactory;
    protected $fillable = ['card_no','firstname','middlename','surname','parent_id','facility_id','ward_id','house_no','date_of_birth','modified_by'];

    public function bookings(){
        return $this->hasMany(Booking::class);
    }


    public function vaccinations(){
        return $this->belongsToMany(Vaccination::class,'child_vaccinations','child_id','vaccination_id');
    }

    public function users(){
        return $this->belongsTo(User::class);
    }

    public function facilities(){
        return $this->belongsTo(Facility::class,null,'card_no');
    }

    public function wards(){
        return $this->belongsTo(Ward::class);
    }
    
    public function health_workers(){
        return $this->belongsTo(HealthWorker::class);
    }

    public function parents_guardians(){
        return $this->belongsToMany(ParentsGuardians::class,'parents_guardians_children','child_id','parents_guardians_id');
       }

     
}
