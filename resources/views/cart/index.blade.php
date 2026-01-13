{{-- ================================================
FILE: resources/views/cart/index.blade.php
FUNGSI: Halaman keranjang belanja
================================================ --}}

@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">
        <i class="bi bi-cart3 me-2"></i>Keranjang Belanja
    </h2>

    @if($cart && $cart->items->count())
    <div class="row">
        {{-- Cart Items --}}
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 50%" class="ps-4">Produk</th>
                                    <th class="text-center">Harga</th>
                                    <th class="text-center">Jumlah</th>
                                    <th class="text-end">Subtotal</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cart->items as $item)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            {{-- PERBAIKAN: Menggunakan $item->product->image_url --}}
                                            <img src="{{ $item->product->image_url }}" class="rounded me-3 border" width="60"
                                                height="60" style="object-fit: cover; background: #f8f9fa;">
                                            <div>
                                                <a href="{{ route('catalog.show', $item->product->slug) }}"
                                                    class="text-decoration-none text-dark fw-bold d-block">
                                                    {{ Str::limit($item->product->name, 40) }}
                                                </a>
                                                <span class="badge bg-light text-dark fw-normal border">
                                                    {{ $item->product->category->name }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        {{ $item->product->formatted_price }}
                                    </td>
                                    <td class="text-center">
                                        <form action="{{ route('cart.update', $item->id) }}" method="POST"
                                            class="d-inline-flex align-items-center justify-content-center">
                                            @csrf
                                            @method('PATCH')
                                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="1"
                                                max="{{ $item->product->stock }}"
                                                class="form-control form-control-sm text-center shadow-none" style="width: 70px;"
                                                onchange="this.form.submit()">
                                        </form>
                                    </td>
                                    <td class="text-end fw-bold">
                                        Rp {{ number_format($item->calculated_subtotal, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center">
                                        <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm text-danger"
                                                onclick="return confirm('Hapus item ini?')">
                                                <i class="bi bi-trash fs-5"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Order Summary --}}
        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="mb-0 fw-bold">Ringkasan Belanja</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Total Harga ({{ $cart->items->sum('quantity') }} barang)</span>
                        <span class="fw-medium">
                            Rp {{ number_format($cart->calculated_total, 0, ',', '.') }}
                        </span>
                    </div>

                    <hr class="text-muted">

                    <div class="d-flex justify-content-between mb-4">
                        <span class="fw-bold">Total Belanja</span>
                        <span class="fw-bold text-primary fs-4">
                            Rp {{ number_format($cart->calculated_total, 0, ',', '.') }}
                        </span>
                    </div>

                    <a href="{{ route('checkout.index') }}" class="btn btn-primary w-100 btn-lg mb-2 py-3 shadow-sm">
                        <i class="bi bi-credit-card me-2"></i>Lanjut ke Checkout
                    </a>
                    <a href="{{ route('catalog.index') }}" class="btn btn-link w-100 text-decoration-none text-muted">
                        <i class="bi bi-arrow-left me-2"></i>Kembali Belanja
                    </a>
                </div>
            </div>
        </div>
    </div>
    @else
    {{-- Empty Cart --}}
    <div class="card border-0 shadow-sm py-5">
        <div class="card-body text-center">
            <div class="mb-4">
                <i class="bi bi-cart-x text-muted" style="font-size: 6rem;"></i>
            </div>
            <h4 class="fw-bold">Wah, keranjang belanjamu kosong!</h4>
            <p class="text-muted mb-4">Yuk, cari produk favoritmu dan masukkan ke keranjang.</p>
            <a href="{{ route('catalog.index') }}" class="btn btn-primary btn-lg px-5 py-3">
                <i class="bi bi-bag me-2"></i>Mulai Belanja
            </a>
        </div>
    </div>
    @endif
</div>
@endsection