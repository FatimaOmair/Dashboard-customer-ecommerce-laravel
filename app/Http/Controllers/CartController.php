<?php
namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::where('user_id', Auth::id())->get();
        return view('cart', compact('cartItems'));
    }

    public function add(Request $request)
    {
        $product = Product::find($request->id);

        if (!$product) {
            return redirect()->route('cart.index')->with('error', 'Product not found.');
        }

        $cartItem = Cart::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'quantity' => $request->quantity,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Product added to cart successfully!');
    }

    public function update(Request $request)
    {
        $cartItem = Cart::find($request->id);

        if ($cartItem && $cartItem->user_id == Auth::id()) {
            $cartItem->quantity = $request->quantity;
            $cartItem->save();

            // Calculate new totals
            $cartItems = Cart::where('user_id', Auth::id())->get();
            $subtotal = $cartItems->sum(function ($item) {
                return $item->quantity * $item->product->price;
            });
            $tax = $subtotal * 0.1; // Example tax calculation
            $total = $subtotal + $tax;
            $itemSubtotal = $cartItem->quantity * $cartItem->product->price;

            return response()->json([
                'success' => true,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total' => $total,
                'itemSubtotal' => $itemSubtotal,
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Item not found or unauthorized.'], 404);
    }

    public function remove(Request $request)
    {
        $cartItem = Cart::find($request->id);

        if ($cartItem && $cartItem->user_id == Auth::id()) {
            $cartItem->delete();
            return redirect()->route('cart.index')->with('success', 'Product removed from cart successfully!');
        }

        return redirect()->route('cart.index')->with('error', 'Product not found in your cart.');
    }

    public function clear()
    {
        Cart::where('user_id', Auth::id())->delete();

        return redirect()->route('cart.index')->with('success', 'Cart cleared successfully!');
    }
}
