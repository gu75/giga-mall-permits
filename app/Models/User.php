<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'shop_name',
        'floor_location',
        'cell_no',
        'cnic',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isTenant(): bool
    {
        return $this->role === 'tenant';
    }

    public function isOperations(): bool
    {
        return $this->role === 'operations';
    }

    public function isHse(): bool
    {
        return $this->role === 'hse';
    }

    public function isSecurity(): bool
    {
        return $this->role === 'security';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isMallStaff(): bool
    {
        return in_array($this->role, ['operations', 'hse', 'security', 'admin']);
    }

    public function workPermits()
    {
        return $this->hasMany(WorkPermit::class, 'tenant_id');
    }

    public function materialPermits()
    {
        return $this->hasMany(MaterialPermit::class, 'tenant_id');
    }
}
