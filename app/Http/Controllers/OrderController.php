<?php
namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;

class OrderController extends Controller
{
    public function __construct()
    {
        Config::$serverKey    = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized  = true;
        Config::$is3ds        = true;
    }

    public function index()
    {
        $orders = auth()->user()->orders()
            ->with(['items.product'])
            ->latest()
            ->paginate(10);

        // PASTIKAN FILE ADA DI: resources/views/orders/index.blade.php
        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        // 1. Authorization Check
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke pesanan ini.');
        }

        $snapToken = $order->snap_token;

        // 2. Generate Snap Token jika status pending dan belum ada token
        if ($order->status === 'pending' && ! $snapToken) {
            $params = [
                'transaction_details' => [
                    'order_id'     => $order->order_number . '-' . time(),
                    'gross_amount' => (int) $order->total_amount,
                ],
                'customer_details'    => [
                    'first_name' => auth()->user()->name,
                    'email'      => auth()->user()->email,
                    'phone'      => $order->shipping_phone,
                ],
                'callbacks'           => [
                    'finish'   => route('orders.success', $order->id),
                    'unfinish' => route('orders.show', $order->id),
                    'error'    => route('orders.show', $order->id),
                ],
            ];

            try {
                $snapToken = Snap::getSnapToken($params);
                $order->update(['snap_token' => $snapToken]);
            } catch (\Exception $e) {
                Log::error('Midtrans Error: ' . $e->getMessage());
            }
        }

        // 3. Load relasi untuk ditampilkan di view
        $order->load(['items.product', 'items.product.images']);

        // PERBAIKAN: Gunakan view 'orders.show' (untuk user)
        // pastikan filenya ada di resources/views/orders/show.blade.php
        return view('order.show', compact('order', 'snapToken'));
    }

    public function success(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }
        return view('orders.success', compact('order'));
    }
    public function pending(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }
        return view('orders.pending', compact('order'));
    }
}