<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentsGuardians extends Model
{
    use HasFactory;
    protected $fillable = ['nida_id', 'firstname', 'middlename', 'lastname',];

    public function children()
    {
        return $this->belongsToMany(Child::class, 'parents_guardians_children', 'parents_guardians_id', 'child_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
