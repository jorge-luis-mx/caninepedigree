<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DogSale extends Model
{
    use HasFactory;


    protected $table = 'dog_sales';
    protected $primaryKey = 'sale_id';

    protected $fillable = [
        'dog_id',
        'seller_id',
        'buyer_id',
        'buyer_email',
        'sale_date',
        'payment_method',
        'price',
        'status',
        'token'
    ];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public function dog() {
        return $this->belongsTo(Dog::class, 'dog_id');
    }

    public function seller() {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function buyer() {
        return $this->belongsTo(User::class, 'buyer_id');
    }


}
