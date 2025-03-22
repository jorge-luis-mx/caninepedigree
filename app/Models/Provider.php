<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Provider extends Model
{
    use HasFactory;

    protected $table = 'providers';
    protected $primaryKey = 'pvr_id';
    public $incrementing = true; 
    
    protected $fillable = [
        'pvr_name',
        'pvr_contact',
        'pvr_email',
        'pvr_phone',
        'pvr_country',
        'pvr_status',
    ];

    const CREATED_AT = 'pvr_created';
    const UPDATED_AT = 'pvr_updated';



    public function auth(): HasMany
    {
        return $this->hasMany(User::class, 'pvr_id','pvr_id'); 
    }

    
    public function airports(): HasMany
    {
        return $this->hasMany(Airport::class, 'pvr_id','pvr_id'); 
    }

    
    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class, 'sale_provider_id','pvr_id'); 
    }

    
    public function billing(): HasMany
    {
        return $this->hasMany(Billing::class, 'pvr_id','pvr_id'); 
    }

    public function retentions():HasMany
    {
        return $this->hasMany(Retention::class,'pvr_id','pvr_id');
    }
}
