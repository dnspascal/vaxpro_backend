<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentsGuardians extends Model
{
    use HasFactory;
    protected $fillable = ['name','contacts','password','address_district','address_name','modified_by'];
}
