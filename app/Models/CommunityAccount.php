<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunityAccount extends Model
{
    use HasFactory;
    protected $fillable = ['ward_id','contacts','modified_by','type'];
    public function wards(){
        return $this->belongsTo(Ward::class);
    }
}
