<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BreedingRequest extends Model
{
    use HasFactory;

    protected $table = 'breeding_requests';
    protected $primaryKey = 'request_id';
    public $incrementing = true; 
    
    protected $fillable = [
        'female_dog_id', 
        'male_dog_id',
        'requester_id', 
        'owner_id',
        'payment_required',
        'payment_type',
        'status',
        'miting_date',
        'delivery_date'
    ];

    /**
     * Relación con el perro hembra.
     * Una solicitud de cruza pertenece a una perra.
     * (breeding_requests.female_dog_id → dogs.dog_id)
     */

    public function femaleDog()
    {
        return $this->belongsTo(Dog::class, 'female_dog_id');
    }

     /**
     * Relación con el perro macho.
     * Una solicitud de cruza pertenece a un perro macho.
     * (breeding_requests.male_dog_id → dogs.dog_id)
     */
    public function maleDog()
    {
        return $this->belongsTo(Dog::class, 'male_dog_id');
    }

    /**
     * Relación con el usuario solicitante.
     * Una solicitud de cruza pertenece a un usuario que la hizo.
     * (breeding_requests.requester_id → user_profiles.user_id)
     */

    public function requester()
    {
        return $this->belongsTo(UserProfile::class, 'requester_id');
    }

    /**
     * Relación con el dueño del perro solicitado.
     * Una solicitud de cruza pertenece al dueño del perro que recibe la solicitud.
     * (breeding_requests.owner_id → user_profiles.user_id)
     */
    public function owner()
    {
        return $this->belongsTo(UserProfile::class, 'owner_id');
    }

    public function photos()
    {
        return $this->hasMany(BreedingPhoto::class,'breeding_request_id','request_id');
    }
}
