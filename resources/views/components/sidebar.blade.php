<nav class="nxl-navigation">
    <div class="navbar-wrapper">
        <div class="m-header">
            <a href="{{ route('cashier.index') }}" class="b-brand">
                <div class="logo logo-lg">
                    <h5 class="fw-bold mb-0 text-dark">POS SYSTEM</h5>
                </div>
                <div class="logo logo-sm">
                    <h5 class="fw-bold mb-0 text-dark">PS</h5>
                </div>
            </a>
        </div>


        <div class="navbar-content">
            <ul class="nxl-navbar">
                <li class="nxl-item nxl-caption">
                    <label>MENU UTAMA</label>
                </li>


                {{-- Kasir --}}
                <li class="nxl-item {{ request()->routeIs('cashier.*') ? 'active' : '' }}">
                    <a href="{{ route('cashier.index') }}" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-shopping-cart"></i></span>
                        <span class="nxl-mtext">Kasir</span>
                    </a>
                </li>

                {{-- Produk --}}
                <li class="nxl-item {{ request()->routeIs('products.*') ? 'active' : '' }}">
                    <a href="{{ route('products.index') }}" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-box"></i></span>
                        <span class="nxl-mtext">Produk</span>
                    </a>
                </li>

                {{-- Kategori --}}
                <li class="nxl-item {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                    <a href="{{ route('categories.index') }}" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-layers"></i></span>
                        <span class="nxl-mtext">Kategori</span>
                    </a>
                </li>

                {{-- Laporan --}}
                <li class="nxl-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                    <a href="{{ route('reports.index') }}" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-bar-chart-2"></i></span>
                        <span class="nxl-mtext">Laporan</span>
                    </a>
                </li>

                {{-- Divider --}}
                <li class="nxl-item nxl-caption">
                    <label>PENGATURAN</label>
                </li>

                {{-- Logout --}}
                <li class="nxl-item">
                    <a href="{{ route('logout') }}" class="nxl-link"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <span class="nxl-micon"><i class="feather-log-out"></i></span>
                        <span class="nxl-mtext">Logout</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>

            {{-- Info Card (Opsional - Ciri Khas Duralux) --}}
            <div class="card text-center m-3 bg-soft-primary border-0">
                <div class="card-body p-3">
                    <i class="feather-user fs-4 text-primary"></i>
                    <p class="fs-11 my-2 text-dark">Shift Aktif: <br> <strong>{{ Auth::user()->name ?? 'Kasir' }}</strong></p>
                </div>
            </div>
        </div>
    </div>
</nav>
<script>
    // Simpan status ke browser
miniButton.addEventListener('click', function() {
    body.classList.toggle('nxl-sidebar-hide');
    const isHidden = body.classList.contains('nxl-sidebar-hide');
    localStorage.setItem('sidebar-state', isHidden ? 'hidden' : 'visible');
});

// Cek saat halaman dimuat
if (localStorage.getItem('sidebar-state') === 'hidden') {
    body.classList.add('nxl-sidebar-hide');
}
</script>
