<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Child extends Model
{
    use HasFactory;
    protected $fillable = ['firstname','middlename','surname','parent_id','facility_id','address_district','address_name','house_no','date_of_birth','modified_by'];

}
