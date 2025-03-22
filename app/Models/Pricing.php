<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pricing extends Model
{
    use HasFactory;

    protected $table = 'pricing';
 
    protected $primaryKey = 'pricing_id';
    public $incrementing = true; 

    protected $fillable = [
        'pvr_airport_id',
        'pvr_ser_id',
        'pricing_polygon_id',
        'pvr_map_id',
        'pricing_oneway',
        'pricing_roundtrip',
        'pricing_currency',
        'pricing_status'

    ];

    const CREATED_AT = 'pricing_created';
    const UPDATED_AT = 'pricing_updated';

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'pvr_ser_id', 'pvr_ser_id');
    }

    public function map()
    {
        return $this->belongsTo(Map::class, 'pvr_map_id', 'pvr_map_id');
    }
    
}
