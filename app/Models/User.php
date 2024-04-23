<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'role',
        'ward_id',
        'password',
        'facility_id',
        'contacts',
        'account_type',
        'modified_by'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
           
            'password' => 'hashed',
        ];
    }

    public function wards(){
        return $this->belongsTo(Ward::class);
    }

    public function facilities(){
        return $this->belongsTo(Facility::class);
    }

    public function health_workers(){
        return $this->hasMany(HealthWorker::class);
    }

    public function children(){
        return $this->hasMany(Child::class);
    }


    //user who adds the facility
    public function modified_by(){
        return $this->hasMany(Facility::class);
    }
}
