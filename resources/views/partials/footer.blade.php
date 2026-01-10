{{-- ================================================
FILE: resources/views/partials/footer.blade.php
================================================ --}}

<footer class="bg-dark text-light pt-5 pb-3 mt-5">
    <div class="container">
        <div class="row g-4">

            <div class="col-lg-4">
                <h5 class="text-white">
                    <i class="bi bi-dribbble me-2"></i>Assalaam Football Store
                </h5>
                <p class="text-secondary">
                    Official store jersey & apparel sepak bola Assalaam.
                </p>
            </div>

            <div class="col-lg-2">
                <h6>Menu</h6>
                <ul class="list-unstyled">
                    <li><a href="{{ route('catalog.index') }}" class="text-secondary">Katalog</a></li>
                    <li><a href="#" class="text-secondary">Tentang Kami</a></li>
                </ul>
            </div>

            <div class="col-lg-2">
                <h6>Bantuan</h6>
                <ul class="list-unstyled">
                    <li><a href="#" class="text-secondary">FAQ</a></li>
                    <li><a href="#" class="text-secondary">Cara Order</a></li>
                </ul>
            </div>

            <div class="col-lg-4">
                <h6>Kontak</h6>
                <p class="text-secondary mb-1">Bandung, Jawa Barat</p>
                <p class="text-secondary mb-1">+62 8xxx xxxx xxxx</p>
                <p class="text-secondary">store@assalaamfootball.id</p>
            </div>
        </div>

        <hr class="border-secondary my-4">

        <p class="text-center text-secondary small mb-0">
            &copy; {{ date('Y') }} Assalaam Football Store
        </p>
    </div>
</footer>