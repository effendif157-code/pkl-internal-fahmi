<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = [
        'cart_id',
        'product_id',
        'quantity',
    ];

    // 🔥 RELASI KE PRODUCT (INI YANG KURANG)
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // relasi balik ke cart (opsional tapi dianjurkan)
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }
    public function getSubtotalAttribute()
    {
        return $this->product->price * $this->quantity;
    }
}
