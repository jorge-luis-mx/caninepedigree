<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $table = 'locations';
    protected $primaryKey = 'id';
    public $incrementing = true; 
    
    protected $fillable = [
        'postal_code',
        'city',
        'state'
    ];

    public function userProfiles()
    {
        return $this->hasMany(UserProfile::class);
    }

}
