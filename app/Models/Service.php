<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use HasFactory;


    protected $table = 'provider_conf_services';
    protected $primaryKey = 'pvr_ser_id';
    public $incrementing = true; 

    protected $fillable = [
        'pvr_id',
        'ser_typ_id',
        'pvr_conf_airport_id',
        'pvr_ser_thumb',
        'pvr_ser_status'
    ];

    const CREATED_AT = 'pvr_ser_created';
    const UPDATED_AT = 'pvr_ser_updated';


    public function airport(): BelongsTo
    {
        return $this->belongsTo(Airport::class,'pvr_conf_airport_id','pvr_airport_id');
    }

    public function pricing(): HasMany
    {
        return $this->hasMany(Pricing::class, 'pvr_ser_id', 'pvr_ser_id');

    }
    
    public function serviceType()
    {
          return $this->belongsTo(ServiceType::class, 'ser_typ_id','ser_typ_id');
    }

}
