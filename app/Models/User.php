<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;


    protected $table = 'users';
    protected $primaryKey = 'user_id';
    public $incrementing = true; 

    protected $fillable = [
        'profile_id',
        'user_name',
        'password',
        'role_id',
        'locale',
        'status',
    ];

    protected $hidden = [
        'password',
    ];
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    
    // Especifica que el campo de contraseña es `p_auth_password`
    public function getAuthPassword()
    {
        return $this->password;
    }

    public function userprofile(): BelongsTo
    {
        return $this->belongsTo(UserProfile::class, 'profile_id', 'profile_id');
    }

    // Relación con el rol
    //Un usuario pertenece a un solo rol, pero un rol puede tener muchos usuarios.
    public function role()
    {
        return $this->belongsTo(Role::class,'role_id','id');
    }
}
