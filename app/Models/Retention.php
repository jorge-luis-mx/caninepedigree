<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Retention extends Model
{
    use HasFactory;


    protected $table = 'providers_app_fee';
    protected $primaryKey = 'app_fee_id';
    public $incrementing = true; 

    protected $fillable = [
        'pvr_id',
        'pvr_airport_id',
        'app_fee_pvr_share',
        'app_fee_polygon_id',
        'app_fee_polygon_share',
        'app_fee_status'
    ];

    const CREATED_AT = 'app_fee_created';
    const UPDATED_AT = 'app_fee_updated';

    
    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class, 'pvr_id', 'pvr_id');
    }

}
