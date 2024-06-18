<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentsGuardiansChild extends Model
{
    use HasFactory;
    protected $fillable = ['nida_id','card_no','relationship_with_child'];
    
}
