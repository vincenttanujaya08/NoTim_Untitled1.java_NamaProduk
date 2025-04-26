<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'cooperative_id',
        'role_id',
        'name',
        'email',
        'password',
        'phone',
        'address'
    ];

    protected $hidden = ['password', 'remember_token'];

    public function cooperative()
    {
        return $this->belongsTo(Cooperative::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function farmer()
    {
        return $this->hasOne(Farmer::class);
    }

    public function buyer()
    {
        return $this->hasOne(Buyer::class);
    }
}
