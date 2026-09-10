@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<section class="stats">
    <div class="stat-card">
        <div class="stat-title">Aktivitas Jurnal Hari Ini</div>
        <div class="stat-value">
            <strong>{{ $totalJurnalHariIni }}</strong>
            <span>Terisi</span>
        </div>
        <span class="today-badge">Hari Ini</span>
    </div>

    <div class="stat-card">
        <div class="stat-title">Jumlah Guru</div>
        <div class="stat-value">
            <strong>{{ $totalGuru }}</strong>
            <span>Terdaftar</span>
        </div>
        <div class="stat-icon">
            <span class="material-symbols-outlined">person_add</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-title">Jumlah Kelas</div>
        <div class="stat-value">
            <strong>{{ $totalKelas }}</strong>
            <span>Kelas</span>
        </div>
        <div class="stat-icon">
            <span class="material-symbols-outlined">meeting_room</span>
        </div>
    </div>
</section>

<section class="activity-card">
    <div class="activity-header">
        <h3 class="activity-title">Aktivitas Jurnal Terkini</h3>
        <p class="activity-description">Daftar entri jurnal pembelajaran harian dan status validasi kurikulum.</p>
    </div>

    <form action="{{ route('admin.dashboard') }}" method="GET" class="filters">
    <!-- Input Search -->
    <div class="search-box">
        <span class="material-symbols-outlined">search</span>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari jurnal, nama guru, kelas, mapel...">
    </div>

    <!-- Filter Status -->
    <select name="status" class="filter-select" onchange="this.form.submit()">
        <option value="">Semua Status Validasi</option>
        <option value="Valid" {{ request('status') == 'Valid' ? 'selected' : '' }}>Valid</option>
        <option value="Menunggu" {{ request('status') == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
        <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
    </select>

    <!-- Filter Kelas -->
    <select name="kelas_id" class="filter-select" onchange="this.form.submit()">
        <option value="">Semua Kelas</option>
        @foreach($kelases as $kelas)
            <option value="{{ $kelas->id }}" {{ request('kelas_id') == $kelas->id ? 'selected' : '' }}>
                {{ $kelas->nama_kelas }}
            </option>
        @endforeach
    </select>

    <!-- Filter Tanggal (Dinamis / Input Date) -->
    <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="filter-select" onchange="this.form.submit()">

    <!-- Tombol Reset Filter jika sedang memfilter -->
    @if(request()->anyFilled(['search', 'status', 'kelas_id', 'tanggal']))
        <a href="{{ route('admin.dashboard') }}" class="btn-reset" style="padding: 8px 12px; font-size: 14px; text-decoration: none;">Reset Filter</a>
    @endif
</form>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>NO</th>
                    <th>TANGGAL &<br>JAM</th>
                    <th>GURU</th>
                    <th>MATA<br>PELAJARAN</th>
                    <th>KELAS</th>
                    <th>KEHADIRAN</th>
                    <th>STATUS<br>VALIDASI</th>
                    <th>AKSI</th>
                </tr>
            </thead>

           <tbody>
    @forelse($jurnals as $index => $item)
        <tr>
            <td class="number">{{ $jurnals->firstItem() + $index }}</td>
            <td>
                <span class="date">{{ $item->created_at->format('d M Y') }}</span>
                <span class="time">{{ $item->jam_ke }}</span>
            </td>
            <td class="teacher">{{ $item->guru->nama }}</td>
            <td class="subject">{{ $item->jadwal->mapel->nama_mapel ?? '-' }}</td>
            <td><span class="class-badge">{{ $item->kelas->nama_kelas }}</span></td>
            <td class="attendance">
                <strong>{{ $item->jumlah_hadir }}/{{ $item->total_siswa }}</strong>
            </td>
            <td><span class="status {{ strtolower($item->status) }}">{{ $item->status }}</span></td>
            <td>
                <button class="action">
                    <span class="material-symbols-outlined">visibility</span>
                </button>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="8" style="text-align: center;">Belum ada data jurnal.</td>
        </tr>
    @endforelse
</tbody>
        </table>
    </div>

    <div class="bottom">
       {{ $jurnals->links() }}
    </div>
</section>
@endsection