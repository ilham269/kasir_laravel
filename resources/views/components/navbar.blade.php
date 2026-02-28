<!-- resources/views/components/navbar.blade.php -->

<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm border-bottom sticky-top">
    <div class="container-fluid px-4">
        <!-- Brand -->
        <a class="navbar-brand fw-bold text-primary" href="{{ route('home') ?? '/' }}">
            Nama App Kamu
        </a>

        <!-- Mobile Toggle -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarContent">
            <!-- Left: Kosong atau tambah menu -->
            <ul class="navbar-nav me-auto"></ul>

            <!-- Right: Search + Notif + User -->
            <div class="d-flex align-items-center gap-3">

                <!-- Search -->
                <div class="dropdown">
                    <a class="btn btn-link text-dark px-2 py-2 rounded-circle" href="#" data-bs-toggle="dropdown">
                        <i data-feather="search"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end shadow p-3" style="width: 320px;">
                        <div class="input-group">
                            <span class="input-group-text"><i data-feather="search"></i></span>
                            <input type="text" class="form-control" placeholder="Cari sesuatu...">
                        </div>
                    </div>
                </div>

                <!-- Notification -->

                <!-- User Dropdown -->
               <!-- User Profile Dropdown (ganti bagian ini saja) -->
<div class="dropdown ms-2 ms-md-3">
    <a class="d-flex align-items-center text-decoration-none pe-0 dropdown-toggle"
       href="#"
       role="button"
       data-bs-toggle="dropdown"
       aria-expanded="false">
        <img src="{{ Auth::user()?->profile_photo_url ?? asset('images/avatar/default.png') }}"
             alt="User Avatar"
             class="rounded-circle me-2"
             width="38" height="38"
             style="object-fit: cover; border: 2px solid #e9ecef;">
        <div class="d-none d-md-block text-start">
            <div class="fw-semibold text-dark">{{ Auth::user()?->name ?? 'Admin' }}</div>
            <small class="text-muted d-block" style="line-height: 1;">{{ Auth::user()?->email ?? 'admin@example.com' }}</small>
        </div>
    </a>

    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3 mt-2" style="min-width: 240px;">
        <li class="dropdown-header px-4 py-3 bg-light border-bottom">
            <div class="d-flex align-items-center">
                <img src="{{ Auth::user()?->profile_photo_url ?? asset('images/avatar/default.png') }}"
                     alt="User Avatar"
                     class="rounded-circle me-3"
                     width="56" height="56"
                     style="object-fit: cover; border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <div>
                    <h6 class="mb-0 fw-bold text-dark">
                        {{ Auth::user()?->name ?? 'Administrator' }}
                        <span class="badge bg-success-subtle text-success ms-2 px-2 py-1 small fw-normal">Online</span>
                    </h6>
                    <small class="text-muted">{{ Auth::user()?->email ?? 'admin@example.com' }}</small>
                </div>
            </div>
        </li>
        <li><hr class="dropdown-divider my-2"></li>
        <li>
            <a class="dropdown-item py-2 px-4" href="{{ route('profile.show') ?? '#' }}">
                <i data-feather="user" class="me-3 text-muted" style="width:20px; height:20px;"></i>
                Profil Saya
            </a>
        </li>
        <li>
            <a class="dropdown-item py-2 px-4" href="{{ route('profile.edit') ?? '#' }}">
                <i data-feather="settings" class="me-3 text-muted" style="width:20px; height:20px;"></i>
                Pengaturan Akun
            </a>
        </li>
        <li><hr class="dropdown-divider my-2"></li>
        <li>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="dropdown-item py-2 px-4 text-danger">
                    <i data-feather="log-out" class="me-3" style="width:20px; height:20px;"></i>
                    Keluar
                </button>
            </form>
        </li>
    </ul>
</div>
            </div>
        </div>
    </div>
</nav>
