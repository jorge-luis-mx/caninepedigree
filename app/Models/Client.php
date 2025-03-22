<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $table = 'clients';
    protected $primaryKey = 'cli_id';
    public $incrementing = true; 

    protected $fillable = [
        'cli_as',
        'cli_fullname',
        'cli_email',
        'cli_phone',
        'cli_lada_phone',
        'cli_country',
        'cli_use_whp',
        'cli_lang',
        'cli_method_pay'


    ];

    const CREATED_AT = 'cli_created';
    const UPDATED_AT = 'cli_updated';


    public function sales()
    {
        return $this->hasMany(Sale::class, 'sale_client_id', 'cli_id');
    }
}
