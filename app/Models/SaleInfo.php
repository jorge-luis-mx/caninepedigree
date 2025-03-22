<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleInfo extends Model
{
    use HasFactory;
    protected $table = 'sales_info';
    protected $primaryKey = 'sif_id';
    public $incrementing = true; 

    protected $fillable = [
        'sif_sale_id',
        'sif_request',
        'sif_language',
        'sif_tag',
        'sif_dim',
        'sif_ref'

    ];

    const CREATED_AT = 'sif_created';
    const UPDATED_AT = 'sif_updated';


   
    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sif_sale_id', 'sale_id');
    }

}
