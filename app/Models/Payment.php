<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Payment extends Model
{
    use HasFactory;


    protected $table = 'payments';
    protected $primaryKey = 'pay_id';
    public $incrementing = true; 

    protected $fillable = [
        'pay_sale_id',
        'pay_transaction_id',
        'pay_amount',
        'pay_fee',
        'pay_currency',
        'pay_status',
        'pay_method',
        'pay_platform'

    ];

    const CREATED_AT = 'pay_created';
    const UPDATED_AT = 'pay_updated';

    public function sales(): BelongsTo
    {
        return $this->belongsTo(Sale::class, 'pay_sale_id', 'sale_id');
    }

}
