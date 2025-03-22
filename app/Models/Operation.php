<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Operation extends Model
{
    use HasFactory;

    protected $table = 'operation';
    protected $primaryKey = 'op_id';
    public $incrementing = true; 
    protected $casts = [
        'op_date' => 'datetime',  // Esto convierte op_date a un objeto Carbon
        'op_time' => 'datetime',
        'op_flight_time' => 'datetime',
    ];
    protected $fillable = [
        'op_sale_id',
        'op_vehicle_qty',
        'op_vechicle_model',
        'op_luggage_qty',
        'op_pax',
        'op_pickup_place',
        'op_dropoff_place',
        'op_date',
        'op_time',
        'op_airline',
        'op_flight_number',
        'op_flight_time',
        'op_way',

    ];

    const CREATED_AT = 'op_created';
    const UPDATED_AT = 'op_updated';


    public function sales(): BelongsTo
    {
        return $this->belongsTo(Sale::class, 'op_sale_id', 'sale_id');
    }

}
