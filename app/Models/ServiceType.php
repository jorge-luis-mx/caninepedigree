<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceType extends Model
{
    use HasFactory;

    protected $table = 'services_type';
 
    protected $primaryKey = 'ser_typ_id';
    public $incrementing = true; 

    protected $fillable = [
        'ser_typ_alias',
        'ser_typ_capacity',
        'ser_typ_avatar',
        'ser_typ_status',

    ];

    const CREATED_AT = 'ser_typ_created';
    const UPDATED_AT = 'ser_typ_updated';

    
    public function services(): HasMany
    {

        return $this->hasMany(Service::class, 'ser_typ_id','ser_typ_id');
    }

}
