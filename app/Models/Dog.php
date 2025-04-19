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

    ];
    protected $fillable = [
        'reg_no',
        'name',
        'breed',
        'color',
        'sex',
        'birthdate',
        'sire_id',
        'dam_id',
        'breeder_id',
        'current_owner_id',
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

}
