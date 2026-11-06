<?php

use Darryldecode\Cart\Facades\CartFacade as Cart;

if (!function_exists('getCartCount')) {
    function getCartCount()
    {
        return Cart::getContent()->sum('quantity') ?? 0;
    }
}
