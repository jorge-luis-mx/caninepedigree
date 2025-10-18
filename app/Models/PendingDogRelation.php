<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendingDogRelation extends Model
{
    use HasFactory;

    protected $table = 'pending_dog_relations';
    protected $primaryKey = 'id';
    public $incrementing = true; 

    protected $fillable = [
        'main_dog_id',
        'relation_type',
        'pending_email',
        'temp_dog_name',
        'token',
        'status'
    ];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    // Relación al perro principal
    public function mainDog()
    {
        return $this->belongsTo(Dog::class, 'main_dog_id', 'dog_id');
    }

}
