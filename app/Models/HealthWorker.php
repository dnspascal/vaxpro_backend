<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthWorker extends Model
{
    use HasFactory;

    protected $fillable = ['staff_id','name','facility_id','contacts','modified_by'];

    public function child_vaccinations(){
        return $this->hasMany(ChildVaccinationSchedule::class);
    }

    public function users(){
        return $this->belongsTo(User::class);
    }
    public function facilities(){
        return $this->belongsTo(Facility::class);
    }

    public function parents_guardians(){
        return $this->hasMany(ParentsGuardians::class, 'modified_by','staff_id');
    }

    public function children(){
        return $this->hasMany(Child::class, 'modified_by','staff_id');
    }
}
