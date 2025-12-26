<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index()
    {
        // Ambil cart + item + product
        $cart = auth()->user()
            ->cart()
            ->with('items.product')
            ->first();

        // Hitung total harga
        $total = 0;
        foreach ($cart->items as $item) {
            $total += $item->quantity * $item->product->price;
        }

        return view('checkout.index', compact('cart', 'total'));
    }

    public function store(Request $request)
    {
        // nanti isi simpan order
    }
}
