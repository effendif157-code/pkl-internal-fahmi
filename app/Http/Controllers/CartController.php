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

            foreach ($cart->items as $item) {
                $item->calculated_subtotal = $item->product->price * $item->quantity;
            }

            $cart->calculated_total = $cart->items->sum('calculated_subtotal');
        }

        return view('cart.index', compact('cart'));
    }

    /**
     * PERBAIKAN: Nama fungsi diubah dari 'add' menjadi 'store'
     * agar sesuai dengan rute: Route::post('/cart/add', [CartController::class, 'store'])
     */
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

    /**
     * PERBAIKAN: Nama fungsi diubah dari 'remove' menjadi 'destroy'
     * atau biarkan 'remove' tapi pastikan di web.php memanggil 'remove'
     */
    public function remove($itemId)
    {
        $this->cartService->removeItem($itemId);

        return back()->with('success', 'Item dihapus dari keranjang.');
    }
}
