@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            {{-- Tombol Kembali --}}
            <div class="mb-4">
                <a href="{{ route('orders.index') }}" class="btn btn-link text-decoration-none p-0 text-muted">
                    <i class="bi bi-arrow-left"></i> Kembali ke Daftar Pesanan
                </a>
            </div>

            <div class="card shadow-sm border-0">
                {{-- Header Order --}}
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="h4 mb-1 fw-bold text-dark">
                                Order #{{ $order->order_number }}
                            </h1>
                            <p class="small text-muted mb-0">
                                <i class="bi bi-clock me-1"></i>{{ $order->created_at->format('d M Y, H:i') }}
                            </p>
                        </div>

                        {{-- Status Badge --}}
                        @php
                            $statusColors = [
                                'pending' => 'bg-warning text-dark',
                                'processing' => 'bg-primary text-white',
                                'shipped' => 'bg-info text-white',
                                'delivered' => 'bg-success text-white',
                                'cancelled' => 'bg-danger text-white',
                            ];
                            $badgeClass = $statusColors[$order->status] ?? 'bg-secondary text-white';
                        @endphp
                        <span class="badge rounded-pill fs-6 px-4 py-2 {{ $badgeClass }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                </div>

                {{-- Detail Items --}}
                <div class="card-body">
                    <h3 class="h6 fw-bold text-uppercase mb-4">Produk yang Dipesan</h3>

                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="table-light">
                                <tr class="small text-muted">
                                    <th class="border-0">Produk</th>
                                    <th class="border-0 text-center">Qty</th>
                                    <th class="border-0 text-end">Harga</th>
                                    <th class="border-0 text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $item->product_name }}</div>
                                        @if($item->variant_name)
                                            <small class="text-muted">Varian: {{ $item->variant_name }}</small>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">
                                        Rp {{ number_format($item->price, 0, ',', '.') }}
                                    </td>
                                    <td class="text-end fw-bold">
                                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="border-top">
                                @if($order->shipping_cost > 0)
                                <tr>
                                    <td colspan="3" class="text-end text-muted small py-2">Ongkos Kirim:</td>
                                    <td class="text-end small py-2">
                                        Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <td colspan="3" class="text-end py-3">
                                        <span class="h6 mb-0 fw-bold">TOTAL BAYAR:</span>
                                    </td>
                                    <td class="text-end py-3">
                                        <span class="h5 mb-0 fw-bold text-primary">
                                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                        </span>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                {{-- Alamat Pengiriman --}}
                <div class="card-body bg-light border-top border-bottom">
                    <h3 class="h6 fw-bold text-uppercase mb-3">Informasi Pengiriman</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <address class="mb-0 small text-muted">
                                <strong class="text-dark d-block mb-1">{{ $order->shipping_name }}</strong>
                                <i class="bi bi-telephone me-1"></i> {{ $order->shipping_phone }}<br>
                                <i class="bi bi-geo-alt me-1"></i> {{ $order->shipping_address }}
                            </address>
                        </div>
                    </div>
                </div>

                {{-- Tombol Bayar --}}
                @if($order->status === 'pending' && $order->snap_token)
                <div class="card-footer bg-white py-4 text-center">
                    <p class="small text-muted mb-3">
                        Klik tombol di bawah untuk menyelesaikan pembayaran via <strong>Midtrans</strong>.
                    </p>
                    <button id="pay-button" class="btn btn-primary btn-lg px-5 shadow-sm fw-bold">
                        <i class="bi bi-credit-card me-2"></i> Bayar Sekarang
                    </button>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if($order->status === 'pending' && $order->snap_token)
@push('scripts')
<script src="{{ config('midtrans.snap_url') }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script type="text/javascript">
    (function() {
        const payButton = document.getElementById('pay-button');
        if (!payButton) return;

        payButton.addEventListener('click', function (e) {
            e.preventDefault();
            
            payButton.disabled = true;
            payButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menghubungkan...';

            window.snap.pay('{{ $order->snap_token }}', {
                onSuccess: function (result) {
                    window.location.href = '{{ route("orders.success", $order->id) }}';
                },
                onPending: function (result) {
                    window.location.href = '{{ route("orders.pending", $order->id) }}';
                },
                onError: function (result) {
                    alert('Pembayaran gagal, silakan coba lagi.');
                    payButton.disabled = false;
                    payButton.innerHTML = '<i class="bi bi-credit-card me-2"></i> Bayar Sekarang';
                },
                onClose: function () {
                    payButton.disabled = false;
                    payButton.innerHTML = '<i class="bi bi-credit-card me-2"></i> Bayar Sekarang';
                }
            });
        });
    })();
</script>
@endpush
@endif

@endsection
