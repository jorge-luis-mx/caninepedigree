<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    use HasFactory;

    protected $table = 'sales';
    protected $primaryKey = 'sale_id';
    public $incrementing = true; 

    protected $casts = [
        'sale_created' => 'datetime',  // Esto convierte op_date a un objeto Carbon
        'sale_updated' => 'datetime',
    ];

    protected $fillable = [
        'sale_client_id',
        'sale_invoice',
        'sale_transfer',
        'sale_service',
        'sale_amount',
        'sale_discount',
        'sale_charge',
        'sale_payment_fee',
        'sale_seller_fee',
        'sale_exchange',
        'sale_currency',
        'sale_promocode',
        'sale_status',
        'sale_gateway',
        'sale_places_type',
        'sale_seller',
        'sale_channel_id',
        'sale_provider_id',
        'sale_ip'
    ];

    const CREATED_AT = 'sale_created';
    const UPDATED_AT = 'sale_updated';
    
    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class, 'sale_provider_id', 'pvr_id');
    }
    
    public function operations(): HasMany
    {
        return $this->hasMany(Operation::class, 'op_sale_id','sale_id'); 
    }

    
    public function salesInfo()
    {
        return $this->hasOne(SaleInfo::class, 'sif_sale_id', 'sale_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'pay_sale_id','sale_id'); 
    }

    
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'sale_client_id', 'cli_id');
    }

    public function channel(): BelongsTo
    {
        return $this->belongsTo(Channel::class, 'sale_channel_id', 'ch_id');
    }


}
