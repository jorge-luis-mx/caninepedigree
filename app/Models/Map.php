<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Map extends Model
{
    use HasFactory;


    protected $table = 'provider_conf_maps';
 
    protected $primaryKey = 'pvr_map_id';
    public $incrementing = true; 

    protected $fillable = [
        'pvr_airport_id',
        'pvr_map_filename',
        'pvr_map_alias'

    ];

    const CREATED_AT = 'pvr_map_created';
    const UPDATED_AT = 'pvr_map_updated';


    public function airport(): BelongsTo
    {
        return $this->belongsTo(Airport::class, 'pvr_airport_id', 'pvr_airport_id');
    }
    
   
   public function pricing():HasMany
   {
       return $this->hasMany(Pricing::class, 'pvr_map_id', 'pvr_map_id');
   }

}
