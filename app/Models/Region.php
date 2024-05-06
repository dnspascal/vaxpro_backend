<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;
    protected $fillable = ['region_name'];

    public function district(){
        return $this->hasMany(District::class);
    }
    public function user(){
        return $this->hasMany(User::class);
    }
}
