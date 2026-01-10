{{-- ================================================
FILE: resources/views/home.blade.php
FUNGSI: Halaman utama website
THEME: Assalaam Football Store (Light & Dark)
================================================ --}}

@extends('layouts.app')

@section('title', 'Beranda')

@section('content')

{{-- ================= HERO SECTION ================= --}}
<section class="hero-section position-relative overflow-hidden">
    <div class="container py-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">

                <span class="badge hero-badge mb-3 px-3 py-2">
                    Official Fahmi Store
                </span>

                <h1 class="hero-title mb-3">
                    Sport Fahmi Store
                </h1>

                <p class="hero-subtitle mb-4">
                    Jersey dan sepatu.
                    Kualitas premium, harga bersahabat.
                </p>

                <div class="d-flex gap-3">
                    <a href="{{ route('catalog.index') }}" class="btn btn-primary btn-lg">
                        <i class="bi bi-bag me-2"></i>Mulai Belanja
                    </a>
                    <a href="#featured" class="btn btn-outline-primary btn-lg">
                        Produk Unggulan
                    </a>
                </div>
            </div>

            <div class="col-lg-6 text-center d-none d-lg-block">
                <img src="https://st4.depositphotos.com/1801497/29168/v/1600/depositphotos_291683946-stock-illustration-vector-logo-sport-shop-black.jpg" alt="Assalaam Football" class="img-fluid hero-image">
            </div>
        </div>
    </div>
</section>

{{-- ================= CATEGORY ================= --}}
<section class="category-section py-5">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge badge-soft-primary mb-2">
                Browse Category
            </span>
            <h2 class="fw-bold mb-2">Kategori Populer</h2>
            <p class="text-muted">Pilih kategori favoritmu</p>
        </div>

        <div class="row g-4 justify-content-center">
            @foreach($categories as $category)
            <div class="col-6 col-md-4 col-lg-2">
                <a href="{{ route('catalog.index', ['category' => $category->slug]) }}"
                    class="category-card-pro text-center">

                    <div class="category-icon-wrap">
                        <img src="{{ $category->img_url }}" alt="{{ $category->name }}" class="category-img">
                    </div>

                    <h6 class="mt-3 mb-1 fw-semibold">{{ $category->name }}</h6>
                    <small>{{ $category->products_count }} Produk</small>

                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ================= FEATURED PRODUCT ================= --}}
<section id="featured" class="featured-section py-5">
    <div class="container">

        <div class="featured-header d-flex flex-wrap justify-content-between align-items-center mb-5 gap-3">
            <div>
                <span class="badge badge-soft-success mb-2">
                    Weekly Picks
                </span>
                <h2 class="fw-bold mb-1">Produk Unggulan</h2>
                <p class="text-muted mb-0">Pilihan terbaik minggu ini</p>
            </div>

            <a href="{{ route('catalog.index') }}" class="btn btn-outline-primary btn-sm">
                Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="row g-4">
            @foreach($featuredProducts as $product)
            <div class="col-6 col-md-4 col-lg-3">
                @include('partials.product-card', ['product' => $product])
            </div>
            @endforeach
        </div>

    </div>
</section>

{{-- ================= PROMO ================= --}}
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-6">
                <div class="promo-card promo-primary">
                    <h3 class="fw-bold">Flash Sale ⚡</h3>
                    <p>Diskon hingga <strong>50%</strong> produk pilihan</p>
                    <a href="{{ route('catalog.index') }}" class="btn btn-light btn-sm">
                        Lihat Promo
                    </a>
                </div>
            </div>

            <div class="col-md-6">
                <div class="promo-card promo-secondary">
                    <h3 class="fw-bold">Member Baru?</h3>
                    <p>Dapatkan voucher <strong>Rp 50.000</strong></p>
                    <a href="{{ route('register') }}" class="btn btn-dark btn-sm">
                        Daftar Sekarang
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ================= LATEST PRODUCT ================= --}}
<section class="section-soft py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Produk Terbaru</h2>
            <p class="text-muted">Update terbaru dari kami</p>
        </div>

        <div class="row g-4">
            @foreach($latestProducts as $product)
            <div class="col-6 col-md-4 col-lg-3">
                @include('partials.product-card', ['product' => $product])
            </div>
            @endforeach
        </div>
    </div>
</section>

@endsection