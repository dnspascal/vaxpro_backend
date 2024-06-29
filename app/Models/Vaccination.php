<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vaccination extends Model
{
    use HasFactory;
    protected $fillable = ['name','frequency','first_dose_after','second_dose_after','third_dose_after','fourth_dose_after','fifth_dose_after'];

    public function children(){
        return $this->belongsToMany(Child::class,'child_vaccinations','vaccination_id','child_id');
    }
}
