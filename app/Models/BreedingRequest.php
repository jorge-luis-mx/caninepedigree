<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BreedingRequest extends Model
{
    use HasFactory;

    protected $table = 'breeding_requests';

    public function femaleDog()
    {
        return $this->belongsTo(Dog::class, 'female_dog_id');
    }

    public function maleDog()
    {
        return $this->belongsTo(Dog::class, 'male_dog_id');
    }

    public function requester()
    {
        return $this->belongsTo(UserProfile::class, 'requester_id');
    }

    public function owner()
    {
        return $this->belongsTo(UserProfile::class, 'owner_id');
    }
}
