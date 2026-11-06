<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'invoice_number',
        'total_amount',
        'status',
        'notes',
        'shipping_address',
        'contact_number'
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * Get the status label attribute.
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Menunggu',
            'processing' => 'Diproses',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => ucfirst($this->status),
        };
    }

    /**
     * Get the status color attribute.
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'warning',
            'processing' => 'info',
            'completed' => 'success',
            'cancelled' => 'danger',
            default => 'secondary',
        };
    }

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke OrderItems - perbaikan nama relasi
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Alias untuk orderItems agar bisa menggunakan items()
    public function items()
    {
        return $this->orderItems();
    }

    // Scope untuk filter status
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', self::STATUS_PROCESSING);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', self::STATUS_CANCELLED);
    }

    // Helper untuk mendapatkan daftar status
    public static function getStatuses()
    {
        return [
            self::STATUS_PENDING => 'Menunggu',
            self::STATUS_PROCESSING => 'Diproses',
            self::STATUS_COMPLETED => 'Selesai',
            self::STATUS_CANCELLED => 'Dibatalkan'
        ];
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_items')
                    ->withPivot('quantity', 'price');
    }
}
