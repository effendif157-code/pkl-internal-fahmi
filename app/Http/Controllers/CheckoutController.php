<?php
namespace App\Http\Controllers;

use App\Services\OrderService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index()
    {
        // Mengambil keranjang user yang sedang login
        $cart = auth()->user()->cart;

        if (! $cart || $cart->items->isEmpty()) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'Keranjang kosong.');
        }

        return view('checkout.index', compact('cart'));
    }

    public function store(Request $request, OrderService $orderService)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'required|string|max:20',
            'address' => 'required|string|max:500',
        ]);

        try {
            // Logic pembuatan order dipindahkan ke Service untuk kebersihan kode
            $order = $orderService->createOrder(
                auth()->user(),
                $validated
            );

            return redirect()
                ->route('orders.show', $order)
                ->with('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran.');
        } catch (\Throwable $e) {
            // Menangkap error jika payment_status atau field lain bermasalah
            return back()
                ->with('error', 'Gagal membuat pesanan: ' . $e->getMessage());
        }
    }
}
