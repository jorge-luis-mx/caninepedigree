<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Airport extends Model
{
    use HasFactory;


    protected $table = 'provider_conf_airports';
    protected $primaryKey = 'pvr_airport_id';
    public $incrementing = true; 

    protected $fillable = [
        'pvr_id',
        'pvr_airport_alias',
        'pvr_airport_api_ref'
    ];

    const CREATED_AT = 'pvr_airport_created';
    const UPDATED_AT = 'pvr_airport_updated';

    public function maps(): HasMany
    {
        return $this->hasMany(Map::class, 'pvr_airport_id', 'pvr_airport_id');
    }
    

    public function services(): HasMany
    {
        return $this->hasMany(Service::class, 'pvr_conf_airport_id','pvr_airport_id');
    }


    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class, 'pvr_id', 'pvr_id');
    }

}
