<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Kasir Sederhana</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --bs-primary: #4f46e5;
            --bs-primary-rgb: 79, 70, 229;
            --primary-color: #4f46e5;
            --primary-hover: #4338ca;
            --primary-gradient: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%);
            --bg-body: #f8fafc;
        }

        /* Kunci agar layout tidak bergeser/meloncat saat pindah halaman */
        html {
            scrollbar-gutter: stable;
            overflow-y: scroll;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-body);
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar {
            background: var(--primary-gradient);
            box-shadow: 0 4px 16px rgba(79, 70, 229, 0.15);
            padding: 0.85rem 0;
        }

        .navbar-brand {
            font-weight: 700;
            letter-spacing: 0.5px;
            font-size: 1.25rem;
        }

        .nav-link {
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            transition: all 0.25s ease-in-out;
            border-radius: 8px;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.15);
            color: #ffffff !important;
        }

        .nav-link.active {
            background: rgba(255, 255, 255, 0.25);
            font-weight: 700;
            color: #ffffff !important;
        }

        .main-content {
            flex: 1 0 auto;
            padding-top: 2rem;
            padding-bottom: 4rem;
        }

        .card {
            border: none;
            border-radius: 14px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
        }

        .btn-primary {
            background-color: var(--primary-color) !important;
            border-color: var(--primary-color) !important;
            color: #ffffff !important;
        }

        .btn-primary:hover,
        .btn-primary:focus,
        .btn-primary:active {
            background-color: var(--primary-hover) !important;
            border-color: var(--primary-hover) !important;
            color: #ffffff !important;
        }

        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: #ffffff;
        }

        .logout-btn {
            border-radius: 8px;
            padding: 0.4rem 1.1rem;
            font-weight: 600;
        }

        footer {
            color: #94a3b8;
            font-size: 0.85rem;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                <i class="fa-solid fa-shop me-2"></i> Kasir Sederhana
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    @if (Auth::user()->role == 'admin')
                        <li class="nav-item mx-1">
                            <a class="nav-link {{ request()->is('dashboard*') || (request()->is('/') && Auth::user()->role == 'admin') ? 'active' : '' }}"
                                href="{{ route('dashboard') }}">
                                <i class="fa-solid fa-gauge-high me-1"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item mx-1">
                            <a class="nav-link {{ request()->is('kategori*') ? 'active' : '' }}"
                                href="{{ route('kategori.index') }}">
                                <i class="fa-solid fa-tags me-1"></i> Kategori
                            </a>
                        </li>
                        <li class="nav-item mx-1">
                            <a class="nav-link {{ request()->is('produk*') ? 'active' : '' }}"
                                href="{{ route('produk.index') }}">
                                <i class="fa-solid fa-box me-1"></i> Produk
                            </a>
                        </li>
                        <li class="nav-item mx-1">
                            <a class="nav-link {{ request()->is('laporan*') ? 'active' : '' }}"
                                href="{{ route('transaksi.laporan') }}">
                                <i class="fa-solid fa-chart-line me-1"></i> Laporan
                            </a>
                        </li>
                    @endif
                    @if (Auth::user()->role == 'kasir')
                        <li class="nav-item mx-1">
                            <a class="nav-link {{ request()->is('transaksi*') ? 'active' : '' }}"
                                href="{{ route('transaksi.index') }}">
                                <i class="fa-solid fa-cart-shopping me-1"></i> Transaksi Kasir
                            </a>
                        </li>
                    @endif
                </ul>

                <div class="navbar-nav ms-auto align-items-center">
                    <span class="text-white-50 me-3 d-none d-lg-block small">
                        Halo, <strong class="text-white">{{ Auth::user()->name }}</strong>
                        <span class="badge bg-light text-primary ms-1" style="font-size: 0.72rem;">
                            {{ strtoupper(Auth::user()->role) }}
                        </span>
                    </span>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="btn btn-outline-light btn-sm logout-btn"
                            onclick="return confirm('Yakin ingin logout?')">
                            <i class="fa-solid fa-right-from-bracket me-1"></i> Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="container main-content">
        @yield('content')
    </main>

    <footer class="text-center mt-auto py-4">
        <div class="container">
            <span>&copy; 2026 Kasir Sederhana &bull; UTS Web Programming 3</span>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
