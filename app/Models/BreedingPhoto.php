<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BreedingPhoto extends Model
{
    use HasFactory;

    protected $table = 'breeding_request_photos'; // El nombre de tu tabla
    protected $primaryKey = 'id';
    public $incrementing = true; 

    protected $fillable = [
        'breeding_request_id',
        'photo_url',
        'photo_public_id', // Agregar esta línea
        'is_main',
    ];

    public function breedingRequest()
    {
        return $this->belongsTo(BreedingRequest::class);
    }
}
