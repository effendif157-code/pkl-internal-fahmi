{{-- ================================================
FILE: resources/views/partials/product-card.blade.php
THEME: Assalaam Football (Light & Dark)
================================================ --}}

<div class="product-card h-100">

    {{-- IMAGE --}}
    <div class="product-thumb position-relative">
        <a href="{{ route('catalog.show', $product->slug) }}">
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="product-img">
        </a>

        {{-- DISCOUNT --}}
        @if($product->has_discount)
        <span class="badge badge-discount">
            -{{ $product->discount_percentage }}%
        </span>
        @endif

        {{-- WISHLIST --}}
        @auth
        <button onclick="toggleWishlist({{ $product->id }})" class="wishlist-btn wishlist-btn-{{ $product->id }}">
            <i class="bi {{ auth()->user()->hasInWishlist($product) ? 'bi-heart-fill active' : 'bi-heart' }}"></i>
        </button>
        @endauth
    </div>

    {{-- BODY --}}
    <div class="product-body d-flex flex-column">

        <small class="product-category">
            {{ strtoupper($product->category->name) }}
        </small>

        <h6 class="product-title">
            <a href="{{ route('catalog.show', $product->slug) }}">
                {{ Str::limit($product->name, 42) }}
            </a>
        </h6>

        {{-- PRICE --}}
        <div class="mt-auto">
            @if($product->has_discount)
            <small class="old-price">
                {{ $product->formatted_original_price }}
            </small>
            @endif

            <div class="product-price">
                {{ $product->formatted_price }}
            </div>

            {{-- STOCK --}}
            @if($product->stock <= 5 && $product->stock > 0)
                <small class="stock-warning">
                    Stok tinggal {{ $product->stock }}
                </small>
                @elseif($product->stock == 0)
                <small class="stock-empty">
                    Stok Habis
                </small>
                @endif
        </div>
    </div>

    {{-- ACTION --}}
    <div class="product-footer">
        <form method="POST" action="{{ route('cart.add') }}">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <input type="hidden" name="quantity" value="1">

            <button class="btn btn-primary btn-sm w-100" {{ $product->stock == 0 ? 'disabled' : '' }}>
                <i class="bi bi-cart-plus me-1"></i>
                Tambah Keranjang
            </button>
        </form>
    </div>
</div>