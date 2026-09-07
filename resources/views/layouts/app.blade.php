<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jurnify - Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .sidebar { width: 250px; background-color: #222d5a; min-height: 100vh; color: #fff; }
        .sidebar .nav-link { color: #a3abcc; padding: 12px 20px; font-weight: 500; border-radius: 8px; margin: 2px 12px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background-color: #313d73; }
        .sidebar .nav-link i { margin-right: 10px; font-size: 1.1rem; }
        .content { flex: 1; padding: 30px; }
        .card-custom { border: none; border-radius: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03); }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar Navigation -->
        <div class="sidebar d-flex flex-column p-3">
            <div class="d-flex align-items-center mb-4 px-2">
                <i class="bi bi-book-half fs-3 me-2"></i>
                <div>
                    <h5 class="fw-bold mb-0">Jurnify</h5>
                    <small style="font-size: 0.65rem; color: #a3abcc;">Kementerian Pendidikan</small>
                </div>
            </div>
            
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="/dashboard" class="nav-link {{ request()->is('dashboard*') ? 'active' : '' }}">
                        <i class="bi bi-grid-1x2"></i> Dashboard
                    </a>
                </li>

                {{-- MENU KHUSUS ADMIN --}}
                @if (Auth::user()->role === 'Admin')
                    <li>
                        <a href="{{ route('admin.guru.index') }}" class="nav-link {{ request()->is('admin/guru*') ? 'active' : '' }}">
                            <i class="bi bi-people"></i> User (Guru)
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.kelas.index') }}" class="nav-link {{ request()->is('admin/kelas*') ? 'active' : '' }}">
                            <i class="bi bi-people-fill"></i> Kelas
                        </a>
                    </li>
                    <li>
                        <a href="#" class="nav-link"><i class="bi bi-journal-bookmark"></i> Mapel</a>
                    </li>
                    <li>
                        <a href="#" class="nav-link"><i class="bi bi-calendar3"></i> Jadwal</a>
                    </li>
                @endif

                {{-- MENU GURU / WALI KELAS --}}
                @if (in_array(Auth::user()->role, ['Guru', 'Wali Kelas']))
                    <li>
                        <a href="#" class="nav-link"><i class="bi bi-journal-text"></i> Isi Jurnal</a>
                    </li>
                    <li>
                        <a href="#" class="nav-link"><i class="bi bi-check2-square"></i> Presensi Murid</a>
                    </li>
                    @if (Auth::user()->role === 'Wali Kelas')
                        <li>
                            <a href="#" class="nav-link"><i class="bi bi-person-lines-fill"></i> Rekap Kelasku</a>
                        </li>
                    @endif
                @endif

                {{-- MENU MURID / SISWA --}}
                @if (Auth::user()->role === 'Murid' || Auth::user()->role === 'Siswa')
                    <li>
                        <a href="#" class="nav-link"><i class="bi bi-calendar-check"></i> Riwayat Kehadiran</a>
                    </li>
                @endif

                <li class="mt-3 border-top pt-3">
                    <a href="#" class="nav-link"><i class="bi bi-person"></i> Profil</a>
                </li>
            </ul>

            <!-- Info User Login & Logout -->
            <div class="border-top pt-3 px-2 mt-auto">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div class="text-truncate me-2">
                        <div class="fw-bold text-white small text-truncate">{{ Auth::user()->nama_user }}</div>
                        <span class="badge bg-primary text-uppercase" style="font-size: 0.65rem;">{{ Auth::user()->role }}</span>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-light border-0" title="Logout">
                            <i class="bi bi-box-arrow-right fs-5 text-danger"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="content">
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>