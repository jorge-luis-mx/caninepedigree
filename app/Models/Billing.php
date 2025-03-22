<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Billing extends Model
{
    use HasFactory;


    protected $table = 'pvr_billing_data';
    protected $primaryKey = 'bill_id';
    public $incrementing = true; 

    protected $fillable = [
        'pvr_id',
        'bill_email_account',
        'bill_bank',
        'bill_account',
        'bill_usa_bank_account',
        'bill_swift',
        'bill_routing',
        'bill_platform',
        
    ];

    const CREATED_AT = 'bill_created';
    const UPDATED_AT = 'bill_updated';


    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class, 'pvr_id', 'pvr_id');
    }
    
}
