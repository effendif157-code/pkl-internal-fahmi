@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="container my-5">
    <h2 class="fw-bold mb-4">Checkout</h2>

    <form action="{{ route('checkout.store') }}" method="POST">
        @csrf

        <div class="row g-4">

            {{-- LEFT : Informasi Pengiriman --}}
            <div class="col-md-7">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="fw-semibold mb-3">📦 Informasi Pengiriman</h5>

                        <div class="mb-3">
                            <label class="form-label">Nama Penerima</label>
                            <input type="text" name="name" class="form-control"
                                   value="{{ auth()->user()->name }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nomor Telepon</label>
                            <input type="text" name="phone" class="form-control"
                                   placeholder="08xxxxxxxxxx" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alamat Lengkap</label>
                            <textarea name="address" rows="4" class="form-control"
                                      placeholder="Masukkan alamat lengkap pengiriman" required></textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT : Ringkasan Pesanan --}}
            <div class="col-md-5">
                <div class="card shadow-sm border-0 sticky-top" style="top: 80px">
                    <div class="card-body">
                        <h5 class="fw-semibold mb-3">🧾 Ringkasan Pesanan</h5>

                        @php
                            $total = 0;
                        @endphp

                        <ul class="list-group list-group-flush mb-3">
                            @foreach ($cart->items as $item)
                                @php
                                    $subtotal = $item->quantity * $item->product->price;
                                    $total += $subtotal;
                                @endphp

                                <li class="list-group-item d-flex justify-content-between px-0">
                                    <div>
                                        <strong>{{ $item->product->name }}</strong><br>
                                        <small class="text-muted">
                                            {{ $item->quantity }} x Rp {{ number_format($item->product->price) }}
                                        </small>
                                    </div>
                                    <span>
                                        Rp {{ number_format($subtotal) }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>

                        <hr>

                        <div class="d-flex justify-content-between fw-bold fs-5 mb-3">
                            <span>Total</span>
                            <span class="text-primary">
                                Rp {{ number_format($total) }}
                            </span>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2">
                            🛒 Buat Pesanan
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>
@endsection
