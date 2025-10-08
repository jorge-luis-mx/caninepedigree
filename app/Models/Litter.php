<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Litter extends Model
{
    use HasFactory;

    // Nombre de la tabla
    protected $table = 'litters';

    // Clave primaria personalizada
    protected $primaryKey = 'litter_id';

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'breeding_request_id',
        'dam_id',
        'sire_id',
        'birth_date',
        'litter_number',
        'total_puppies',
        'surviving_puppies',
        'notes',
        'created_at',
    ];

    // Desactivar updated_at (porque no existe en la tabla)
    const UPDATED_AT = null;

    // Indicar que sí existe created_at
    const CREATED_AT = 'created_at';

    /**
     * Relación con la tabla breeding_requests
     * 
     * 📌 Un registro de camada (litter) proviene de una solicitud de cruza.
     * Por eso, cada camada pertenece a UNA solicitud de cruza específica.
     * 
     * Ejemplo: $litter->breedingRequest → te da la solicitud de cruza que generó esa camada.
     */
    public function breedingRequest()
    {
        return $this->belongsTo(BreedingRequest::class, 'breeding_request_id');
    }

    /**
     * Relación con la tabla dogs (madre)
     * 
     * 📌 La madre de la camada se guarda en la columna dam_id.
     * 
     * Esta relación permite acceder directamente al perro (perra) madre.
     * Ejemplo: $litter->dam → te da la información del perro con ID = dam_id.
     */
    public function dam()
    {
        return $this->belongsTo(Dog::class, 'dam_id');
    }

    /**
     * Relación con la tabla dogs (padre)
     * 
     * 📌 El padre de la camada se guarda en la columna sire_id.
     * 
     * Esta relación permite acceder directamente al perro (padre).
     * Ejemplo: $litter->sire → te da la información del perro con ID = sire_id.
     */
    public function sire()
    {
        return $this->belongsTo(Dog::class, 'sire_id');
    }
}
