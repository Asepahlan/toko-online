<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;

class CartService
{
    public static function count()
    {
        return Session::get('cart', []) ? count(Session::get('cart')) : 0;
    }

    public static function add($product)
    {
        $cart = Session::get('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity']++;
        } else {
            $cart[$product->id] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'image' => $product->image ? asset('storage/' . $product->image) : asset('images/no-image.png')
            ];
        }

        Session::put('cart', $cart);
    }

    public static function content()
    {
        return Session::get('cart', []);
    }
}
