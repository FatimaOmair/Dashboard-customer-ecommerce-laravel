<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::with('products')->where('user_id', Auth::id())->first();
        return view('dashboard.baskets.index', compact('cart'));
    }

    public function viewAllCarts()
    {
        $carts = Cart::with(['user', 'product'])->get();

        return view('dashboard.carts.index', compact('carts'));
    }

    public function create()
    {
        $products = Product::all();
        return view('dashboard.carts.create', compact('products'));
    }

    /**
     * Store a newly created cart in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        $cart->products()->attach($request->product_id, ['quantity' => $request->quantity]);

        return redirect()->route('dashboard.carts.index')->with('success', 'Product added to cart successfully.');
    }

    /**
     * Display the specified cart.
     */
    public function show(Cart $cart)
    {
        if ($cart->user_id !== Auth::id()) {
            abort(403);
        }

        $cart->load('products');
        return view('dashboard.carts.show', compact('cart'));
    }

    /**
     * Show the form for editing the specified cart.
     */
    public function edit(Cart $cart)
    {
        if ($cart->user_id !== Auth::id()) {
            abort(403);
        }

        $products = Product::all();
        return view('dashboard.carts.edit', compact('cart', 'products'));
    }

    /**
     * Update the specified cart in storage.
     */
    public function update(Request $request, Cart $cart)
    {
        if ($cart->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart->products()->updateExistingPivot($request->product_id, ['quantity' => $request->quantity]);

        return redirect()->route('dashboard.carts.index')->with('success', 'Cart updated successfully.');
    }

    /**
     * Remove the specified cart from storage.
     */
    public function destroy(Cart $cart)
    {
        if ($cart->user_id !== Auth::id()) {
            abort(403);
        }

        $cart->delete();

        return redirect()->route('dashboard.carts.index')->with('success', 'Cart deleted successfully.');
    }
}
