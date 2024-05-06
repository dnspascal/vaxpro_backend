<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthWorker extends Model
{
    use HasFactory;

    protected $fillable = ['staff_id','first_name','last_name'];

    public function child_vaccination_schedules(){
        return $this->hasMany(ChildVaccinationSchedule::class);
    }

    public function users(){
        return $this->belongsTo(User::class,"user_id");
    }

    public function children(){
        return $this->hasMany(Child::class,null,'card_no');
    }
}
