<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DogPayment extends Model
{
    use HasFactory;


    protected $table = 'dog_payments';
    protected $primaryKey = 'dog_payment_id';
    public $incrementing = true; 
    
    protected $fillable = [
        'dog_id',
        'payment_id',
    ];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public function dog()
    {
        return $this->belongsTo(Dog::class, 'dog_id');
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }
    
}
