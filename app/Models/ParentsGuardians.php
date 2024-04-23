<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentsGuardians extends Model
{
    use HasFactory;
    protected $fillable = ['nida_id','firstname','middlename','lastname','contacts','password','ward_id','modified_by'];

    public function health_workers(){
        return $this->belongsTo(HealthWorker::class);
    }

    public function wards(){
        return $this->belongsTo(Ward::class);
    }

   
}
