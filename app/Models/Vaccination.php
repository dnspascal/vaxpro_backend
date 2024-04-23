<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vaccination extends Model
{
    use HasFactory;
    protected $fillable = ['name','frequency','interval'];

    public function children(){
        return $this->belongsToMany(Child::class,'child_vaccinations','vaccination_id','child_id');
    }
}
