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
        'status'
    ];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public function breeder():BelongsTo
    {
        return $this->belongsTo(UserProfile::class, 'breeder_id');
    }

}
