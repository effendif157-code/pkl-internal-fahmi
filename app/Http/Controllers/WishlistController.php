<?php
namespace App\Http\Controllers;

use App\Models\Product;

class WishlistController extends Controller
{
    public function __construct()
    {
        // Pastikan semua method hanya bisa diakses user login
        $this->middleware('auth');
    }

    /**
     * Menampilkan halaman wishlist user
     */
    public function index()
    {
        $products = auth()->user()
            ->wishlists()
            ->with(['category', 'primaryImage'])
            ->latest('wishlists.created_at')
            ->paginate(12);

        return view('wishlist.index', compact('products'));
    }

    /**
     * Toggle wishlist (AJAX)
     */
    public function toggle(Product $product)
    {
        $user = auth()->user();

        if ($user->hasInWishlist($product)) {
            // Hapus dari wishlist
            $user->wishlists()->detach($product->id);

            return response()->json([
                'status' => 'removed',
                'count'  => $user->wishlists()->count(),
            ]);
        }

        // Tambahkan ke wishlist
        $user->wishlists()->attach($product->id);

        return response()->json([
            'status' => 'added',
            'count'  => $user->wishlists()->count(),
        ]);
    }
}
