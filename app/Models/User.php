<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'phone_number',
        'address',
        'role',
        'is_active'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean'
    ];

    /**
     * Check if user is admin
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Get the orders for the user.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the cart items for the user.
     */
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function getIsProfileCompleteAttribute()
    {
        return !empty($this->name) &&
               !empty($this->email) &&
               !empty($this->phone) &&
               !empty($this->address);
    }

    public function updateActiveStatus()
    {
        // Cek jika user sudah login dalam 30 hari terakhir
        $lastActivity = $this->last_activity_at ?? $this->created_at;
        $isRecentlyActive = $lastActivity->diffInDays(now()) < 30;

        // Update status aktif
        $this->update([
            'is_active' => $isRecentlyActive
        ]);
    }
}
