<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Payment extends Model
{
    use HasFactory;


    protected $table = 'payments';
    protected $primaryKey = 'payment_id';
    public $incrementing = true; 

    protected $fillable = [
        'breeding_request_id',
        'user_id',
        'amount',
        'payment_method',
        'status'
    ];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public function userProfile():BelongsTo
    {
        return $this->belongsTo(UserProfile::class, 'user_id');
    }

}
