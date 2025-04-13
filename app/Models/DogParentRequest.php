<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DogParentRequest extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'dog_parent_requests';
    protected $primaryKey = 'request_id';
    public $incrementing = true; 

    protected $fillable = [
        'dog_id',
        'parent_type', 
        'email', 
        'token'
    ];


}
