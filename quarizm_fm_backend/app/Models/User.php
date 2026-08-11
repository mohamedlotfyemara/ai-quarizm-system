<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'team', 'phone',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    // customer | coordinator | technician | manager

    public function ticketsAsCustomer()
    {
        return $this->hasMany(Ticket::class, 'customer_id');
    }

    public function ticketsAsTechnician()
    {
        return $this->hasMany(Ticket::class, 'technician_id');
    }

    public function isRole(string $role): bool
    {
        return $this->role === $role;
    }
}
