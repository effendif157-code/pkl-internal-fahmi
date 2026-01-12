<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request; // Tambahkan ini
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
// Tambahkan ini untuk hapus gambar

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Statistik Utama (Cards)
        $stats = [
            'total_revenue'   => Order::whereIn('status', ['processing', 'completed'])
                ->sum('total_amount'),
            'total_orders'    => Order::count(),
            'pending_orders'  => Order::where('status', 'pending')->count(),
            'total_products'  => Product::count(),
            'total_customers' => User::where('role', 'customer')->count(),
            'low_stock'       => Product::where('stock', '<=', 5)->count(),
        ];

        // 2. Data Tabel Pesanan Terbaru
        $recentOrders = Order::with('user')
            ->latest()
            ->take(5)
            ->get();

        // 3. Produk Terlaris
        $topProducts = Product::withCount(['orderItems as sold' => function ($q) {
            $q->select(DB::raw('SUM(quantity)'))
                ->whereHas('order', function ($query) {
                    $query->where('payment_status', 'paid');
                });
        }])
            ->having('sold', '>', 0)
            ->orderByDesc('sold')
            ->take(5)
            ->get();

        // 4. Data Grafik Pendapatan
        $revenueData = Order::select([
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total_amount) as total'),
        ])
            ->where('payment_status', 'paid')
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->keyBy('date');

        $revenueChart = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date    = now()->subDays($i)->format('Y-m-d');
            $dayData = $revenueData->get($date);

            $revenueChart->push([
                'date'  => now()->subDays($i)->format('d M'),
                'total' => $dayData ? $dayData->total : 0,
            ]);
        }

        return view('admin.dashboard', compact('stats', 'recentOrders', 'topProducts', 'revenueChart'));
    }

    /**
     * MENAMBAHKAN FUNGSI DESTROY
     * Menghapus produk beserta file gambar fisiknya.
     */
    public function destroy(Product $product): RedirectResponse
    {
        try {
            // Gunakan transaksi agar database & file tetap sinkron
            DB::beginTransaction();

            // 1. Loop dan hapus semua file gambar fisik dari server storage
            // Pastikan relasi 'images' ada di model Product
            if ($product->images) {
                foreach ($product->images as $image) {
                    if (Storage::disk('public')->exists($image->image_path)) {
                        Storage::disk('public')->delete($image->image_path);
                    }
                }
            }

            // 2. Hapus record produk dari database
            $product->delete();

            DB::commit();

            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Produk dan gambar terkait berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal menghapus produk: ' . $e->getMessage());
        }
    }
}
