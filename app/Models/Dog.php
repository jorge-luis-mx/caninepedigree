<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dog extends Model
{
    use HasFactory;


    protected $table = 'dogs';
    protected $primaryKey = 'dog_id';
    public $incrementing = true; 
    protected $casts = [
        'birthdate' => 'datetime',  // Esto convierte op_date a un objeto Carbon
        'current_owner_id' => 'integer',
    ];
    protected $appends = ['alias_dog'];
    protected $fillable = [
        'reg_no',
        'name',
        'breed',
        'color',
        'sex',
        'url',
        'birthdate',
        'sire_id',
        'dam_id',
        'breeder_id',
        'current_owner_id',
        'created_by_user_id',
        'status',
        'is_puppy',
        'breeding_request_id'
    ];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public function breeder():BelongsTo
    {
        return $this->belongsTo(UserProfile::class, 'breeder_id');
    }

    public function currentOwner()
    {
        return $this->belongsTo(UserProfile::class, 'current_owner_id');
    }

    public function sire()
    {
        return $this->belongsTo(Dog::class, 'sire_id');
    }

    public function dam()
    {
        return $this->belongsTo(Dog::class, 'dam_id');
    }

    public function payments()
    {
        return $this->belongsToMany(Payment::class, 'dog_payments', 'dog_id', 'payment_id');
    }


    // Cruzamientos donde el perro fue el macho
    public function breedingsAsMale()
    {
        return $this->hasMany(BreedingRequest::class, 'male_dog_id','dog_id');
    }

    // Cruzamientos donde el perro fue la hembra
    public function breedingsAsFemale()
    {
        return $this->hasMany(BreedingRequest::class, 'female_dog_id','dog_id');
    }

    // Si este perro es un cachorro, de qué cruza proviene
    public function breedingRequest()
    {
        return $this->belongsTo(BreedingRequest::class, 'breeding_request_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function getAliasDogAttribute()
    {
        $profile = $this->creator->userprofile ?? null;

        if (!$profile) {
            return $this->name;
        }

        if (!empty($profile->kennel_name) && $profile->kennel_name_status == 1) {
            return $profile->kennel_name . ' ' . $this->name;
        }

        if (!empty($profile->kennel_name) && $profile->kennel_name_status == 0) {
            return $profile->last_name . ' ' . $this->name;
        }

        if (empty($profile->kennel_name) && !empty($profile->last_name)) {
            return $profile->last_name . ' ' . $this->name;
        }

        return $this->name;
    }

}
