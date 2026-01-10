<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        $cart = $this->cartService->getCart();

        if ($cart) {
            $cart->load(['items.product.category']);

            // 🔥 HITUNG SUBTOTAL & TOTAL DI BACKEND
            foreach ($cart->items as $item) {
                $item->calculated_subtotal =
                    $item->product->price * $item->quantity;
            }

            $cart->calculated_total =
                $cart->items->sum('calculated_subtotal');
        }

        return view('cart.index', compact('cart'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        $this->cartService->addProduct($product, $request->quantity);

        return back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function update(Request $request, $itemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $this->cartService->updateQuantity($itemId, $request->quantity);

        return back()->with('success', 'Keranjang diperbarui.');
    }

    public function remove($itemId)
    {
        $this->cartService->removeItem($itemId);

        return back()->with('success', 'Item dihapus dari keranjang.');
    }
}
