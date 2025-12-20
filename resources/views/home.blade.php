
@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
{{-- Hero Section --}} <section class="bg-primary text-white py-5"> <div class="container"> <div class="row align-items-center"> <div class="col-lg-6"> <h1 class="display-4 fw-bold mb-3">
Belanja Online Mudah & Terpercaya </h1> <p class="lead mb-4">
Temukan berbagai produk berkualitas dengan harga terbaik.
Gratis ongkir untuk pembelian pertama! </p> <a href="{{ route('catalog.index') }}" class="btn btn-light btn-lg"> <i class="bi bi-bag me-2"></i>Mulai Belanja </a> </div> <div class="col-lg-6 d-none d-lg-block text-center"> <img src="https://media.karousell.com/media/photos/products/2021/8/5/kaos_olahraga__tk__ra__mi__sd__1628203845_58bc3aac_progressive.jpg"
                      alt="Shopping" class="img-fluid" style="max-height: 400px;"> </div> </div> </div> </section>


{{-- Kategori --}}
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-4">Kategori Populer</h2>
        <div class="row g-4">

            @forelse($categories as $category)
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="{{ route('catalog.index', ['category' => $category->slug]) }}"
                       class="text-decoration-none">
                        <div class="card border-0 shadow-sm text-center h-100">
                            <div class="card-body">
                                <img src="{{ $category->img_url }}"
                                     alt="{{ $category->name }}"
                                     class="rounded-circle mb-3"
                                     width="80" height="80"
                                     style="object-fit: cover;">
                                <h6 class="card-title mb-0">{{ $category->name }}</h6>
                                <small class="text-muted">
                                    {{ $category->products_count }} produk
                                </small>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <p class="text-center text-muted">
                    Kategori belum tersedia
                </p>
            @endforelse

        </div>
    </div>
</section>

{{-- Produk Unggulan --}}
<section class="py-5 bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Produk Unggulan</h2>
            <a href="{{ route('catalog.index') }}" class="btn btn-outline-primary">
                Lihat Semua <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        <div class="row g-4">

            @forelse($featuredProducts as $product)
                <div class="col-6 col-md-4 col-lg-3">
                    @include('profile.partials.product-card', ['product' => $product])
                </div>
            @empty
                <p class="text-center text-muted">
                    Produk unggulan belum tersedia
                </p>
            @endforelse

        </div>
    </div>
</section>

{{-- Produk Terbaru --}}
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-4">Produk Terbaru</h2>
        <div class="row g-4">

            @forelse($latestProducts as $product)
                <div class="col-6 col-md-4 col-lg-3">
                    @include('profile.partials.product-card', ['product' => $product])
                </div>
            @empty
                <p class="text-center text-muted">
                    Produk belum tersedia
                </p>
            @endforelse

        </div>
    </div>
</section>
```
@endsection
