<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildVaccinationSchedule extends Model
{
    use HasFactory;
    protected $fillable = ['child_vaccination_id','health_worker_id','facility_id','frequency','vaccination_date','next_vaccination_date','status'];

    public function child_vaccinations(){
        return $this->belongsTo(ChildVaccination::class);
    }

    public function health_workers(){
        return $this->belongsTo(HealthWorker::class, "health_worker_id" ,"staff_id");
    }

    public function facilities(){
        return $this->belongsTo(Facility::class,'facility_id','facility_reg_no');
    }


}
