<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Session;

class CartService
{
    protected $cart;

    public function __construct()
    {
        $this->cart = Session::get('cart', []);
    }

    public function add(Product $product, $quantity)
    {
        $this->cart[$product->id] = [
            'product' => $product,
            'quantity' => $quantity,
            'price' => $product->sale_price ?? $product->regular_price
        ];

        Session::put('cart', $this->cart);
    }

    public function update($productId, $quantity)
    {
        if (isset($this->cart[$productId])) {
            $this->cart[$productId]['quantity'] = $quantity;
        }

        Session::put('cart', $this->cart);
    }

    public function remove($productId)
    {
        unset($this->cart[$productId]);

        Session::put('cart', $this->cart);
    }

    public function clear()
    {
        $this->cart = [];

        Session::put('cart', $this->cart);
    }

    public function content()
    {
        return $this->cart;
    }

    public function subtotal()
    {
        $subtotal = 0;
        foreach ($this->cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        return $subtotal;
    }

    public function tax()
    {
        $taxRate = 0.21; // Assuming 21% tax rate
        return $this->subtotal() * $taxRate;
    }

    public function total()
    {
        return $this->subtotal() + $this->tax();
    }
}
