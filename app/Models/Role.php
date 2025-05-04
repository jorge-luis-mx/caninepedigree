<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    // Relación con los usuarios
    public function users()
    {
        return $this->hasMany(User::class,'role_id','id');
    }
    
    // Relación muchos a muchos con permisos

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions', 'role_id', 'permission_id')
            ->withPivot('status');
    }

}
