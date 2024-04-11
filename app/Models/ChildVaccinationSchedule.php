<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildVaccinationSchedule extends Model
{
    use HasFactory;
    protected $fillable = ['child_vaccination_id','health_worker_id','facility_id','frequency','vaccination_date','next_vaccination_date','status','modified_by'];
}
