<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'image_url',
        'image',
        'category_id',
        'is_active',
        'is_featured',
        'featured',
        'total_sales',
        'sold_count'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'featured' => 'boolean',
        'total_sales' => 'integer',
        'sold_count' => 'integer'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            $product->slug = Str::slug($product->name);
        });

        static::deleting(function($product) {
            DB::table('cart_items')->where('product_id', $product->id)->delete();
            DB::table('order_items')->where('product_id', $product->id)->delete();

            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_active', true)
                    ->orderBy('created_at', 'desc');
    }

    public function scopeBestSellers($query)
    {
        return $query->where('is_active', true)
                    ->orderBy('total_sales', 'desc');
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_items')
                    ->withPivot('quantity', 'price');
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
