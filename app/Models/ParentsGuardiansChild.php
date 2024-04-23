<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentsGuardiansChild extends Model
{
    use HasFactory;
    protected $fillable = ['parents_guardians_id','child_id','relationship_with_child'];
    
}
