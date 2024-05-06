<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentsGuardians extends Model
{
    use HasFactory;

    protected $primaryKey = 'nida_id';

    protected $fillable = ['user_id', 'nida_id', 'firstname', 'middlename', 'lastname'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function child()
    {
        return $this->belongsToMany(Child::class, 'parents_guardians_children', 'parents_guardians_id', 'child_id');
    }
}
