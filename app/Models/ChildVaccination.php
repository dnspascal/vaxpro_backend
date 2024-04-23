<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildVaccination extends Model
{
    use HasFactory;
    protected $fillable = ['child','vaccination','is_active'];
    public function child_vaccination_schedules(){
        return $this->hasMany(ChildVaccinationSchedule::class);
    }
    

}
