<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    use HasFactory;

    protected $table = 'channels';
    protected $primaryKey = 'ch_id';
    public $incrementing = true; 

    protected $fillable = [
        'ch_name',
        'ch_status',
        

    ];

    const CREATED_AT = 'ch_created';
    const UPDATED_AT = 'ch_updated';


   
    public function sales()
    {
         return $this->hasMany(Sale::class, 'sale_channel_id', 'ch_id');
    }
}
