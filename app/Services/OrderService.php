<?php
namespace App\Services;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService
{
    /**
     * Membuat Order baru dari Keranjang belanja.
     */
    public function createOrder(User $user, array $shippingData): Order
    {
        $cart = $user->cart;

        if (! $cart || $cart->items->isEmpty()) {
            throw new \Exception('Keranjang belanja kosong.');
        }

        return DB::transaction(function () use ($user, $cart, $shippingData) {

            // A. VALIDASI STOK & HITUNG TOTAL
            $totalAmount = 0;

            foreach ($cart->items as $item) {
                $product = $item->product;

                if ($item->quantity > $product->stock) {
                    throw new \Exception("Stok produk {$product->name} tidak mencukupi.");
                }

                // Pakai discount_price jika ada, fallback ke price
                $price = $product->discount_price ?? $product->price;

                $totalAmount += $price * $item->quantity;
            }

            // B. BUAT HEADER ORDER (SEKARANG DENGAN payment_status)
            // PERBAIKAN: Menambahkan 'payment_status' agar tidak error SQL
            $order = Order::create([
                'user_id'          => $user->id,
                'order_number'     => 'ORD-' . strtoupper(Str::random(10)),
                'status'           => 'pending',
                'payment_status'   => 'unpaid', // SOLUSI UNTUK ERROR ANDA
                'shipping_name'    => $shippingData['name'],
                'shipping_address' => $shippingData['address'],
                'shipping_phone'   => $shippingData['phone'],
                'total_amount'     => $totalAmount,
            ]);

            // C. PINDAHKAN ITEMS & KURANGI STOK
            foreach ($cart->items as $item) {
                $product = $item->product;
                $price   = $product->discount_price ?? $product->price;

                $order->items()->create([
                    'product_id'   => $product->id,
                    'product_name' => $product->name,
                    'price'        => $price,
                    'quantity'     => $item->quantity,
                    'subtotal'     => $price * $item->quantity,
                ]);

                $product->decrement('stock', $item->quantity);
            }

            // D. GENERATE MIDTRANS SNAP TOKEN (OPTIONAL)
            try {
                $order->load('user');

                $midtransService = new \App\Services\MidtransService();
                $snapToken       = $midtransService->createSnapToken($order);

                $order->update(['snap_token' => $snapToken]);
            } catch (\Exception $e) {
                // Gagal snap token → order tetap valid
            }

            // E. BERSIHKAN KERANJANG
            $cart->items()->delete();

            return $order;
        });
    }
}
