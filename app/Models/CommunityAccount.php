<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunityAccount extends Model
{
    use HasFactory;
    protected $fillable = ['address_district','address_name','contacts','modified_by','type'];
}
