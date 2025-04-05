<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserProfile extends Model
{
    use HasFactory;


    protected $table = 'user_profiles';
    protected $primaryKey = 'profile_id';
    public $incrementing = true; 
    
    protected $fillable = [
        'name',
        'firtsName',
        'lastName',
        'email',
        'phone',
        'address',
        'country',
        'breeder_certificate',
        'picture',
        'status'
    ];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public function user(): HasMany
    {
        return $this->hasMany(User::class, 'profile_id','profile_id'); 
    }

    public function dogs()
    {
        return $this->hasMany(Dog::class, 'current_owner_id');
    }

    // public function dogs():HasMany
    // {
    //     return $this->hasMany(Dog::class, 'breeder_id');
    // }
    
}
