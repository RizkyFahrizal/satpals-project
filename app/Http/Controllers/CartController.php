<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display cart
     */
    public function index()
    {
        $cart = session()->get('cart', []);
        
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price_per_day'] * $item['quantity'];
        }

        return view('public.cart.index', [
            'cart' => $cart,
            'total' => $total,
        ]);
    }

    /**
     * Update cart quantity
     */
    public function update(Request $request, $equipment_id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$equipment_id])) {
            if ($request->quantity <= 0) {
                unset($cart[$equipment_id]);
            } else {
                $cart[$equipment_id]['quantity'] = $request->quantity;
            }
            session()->put('cart', $cart);
        }

        return redirect()->back()
            ->with('success', 'Keranjang berhasil diperbarui!');
    }

    /**
     * Remove item from cart
     */
    public function remove($equipment_id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$equipment_id])) {
            unset($cart[$equipment_id]);
            session()->put('cart', $cart);
        }

        return redirect()->back()
            ->with('success', 'Item berhasil dihapus dari keranjang!');
    }

    /**
     * Clear cart
     */
    public function clear()
    {
        session()->forget('cart');
        return redirect()->back()
            ->with('success', 'Keranjang berhasil dikosongkan!');
    }
}
