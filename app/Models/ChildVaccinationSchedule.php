<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ChildVaccinationSchedule extends Model
{
    use HasFactory;
    protected $fillable = ['child_vaccination_id','child_id', 'health_worker_id', 'facility_id', 'frequency', 'vaccination_date', 'next_vaccination_date','status'];

    public function child_vaccinations()
    {
        return $this->belongsTo(ChildVaccination::class);
    }

    public function child(){
        return $this->belongsTo(Child::class,"child_id","card_no");
    }

    public function health_workers()
    {
        return $this->belongsTo(HealthWorker::class, "health_worker_id", "staff_id");
    }

    public function facilities()
    {
        return $this->belongsTo(Facility::class, 'facility_id', 'facility_reg_no');
    }

    public function getVaccinationDateAttribute($value)
    {

        return Carbon::parse($value)->format('Y-m-d');
    }

    public function getNextVaccinationDateAttribute($value)
    {

        return Carbon::parse($value)->format('Y-m-d');
    }
}
