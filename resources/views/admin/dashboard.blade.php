@extends('layouts.admin')

@section('title', 'Dashboard - Jurnify')

@section('page-title', 'Dashboard')

@section('content')

<section class="stats">
    <div class="stat-card">
        <div class="stat-title">Aktivitas Jurnal Hari Ini</div>
        <div class="stat-value">
            <strong>{{ $jurnalHariIni }}</strong>
            <span>Terisi</span>
        </div>
        <span class="today-badge">Hari Ini</span>
    </div>

    <div class="stat-card">
        <div class="stat-title">Jumlah Guru</div>
        <div class="stat-value">
            <strong>{{ $jumlahGuru }}</strong>
            <span>Terdaftar</span>
        </div>
        <div class="stat-icon">
            <span class="material-symbols-outlined">person</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-title">Jumlah Kelas</div>
        <div class="stat-value">
            <strong>{{ $jumlahKelas }}</strong>
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
        <p class="activity-description">Daftar entri jurnal pembelajaran harian dan status validasi.</p>
    </div>

    <div class="filters">
        <div class="search-box">
            <span class="material-symbols-outlined">search</span>
            <input type="text" placeholder="Cari jurnal, nama guru, kelas, mapel...">
        </div>

        <select class="filter-select">
            <option>Semua Status Validasi</option>
            <option>Disetujui</option>
            <option>Menunggu</option>
            <option>Ditolak</option>
        </select>

        <select class="filter-select">
            <option>Semua Kelas</option>
        </select>

        <select class="filter-select">
            <option>Semua Tanggal</option>
        </select>
    </div>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>NO</th>
                    <th>TANGGAL</th>
                    <th>MATERI</th>
                    <th>KEHADIRAN</th>
                    <th>STATUS GURU</th>
                    <th>VALIDASI</th>
                    <th>AKSI</th>
                </tr>
            </thead>

            <tbody>
                @forelse($jurnalTerbaru as $jurnal)
                    <tr>
                        <td class="number">{{ $loop->iteration }}</td>
                        <td>
                            <span class="date">{{ $jurnal->tanggal->format('d M Y') }}</span>
                        </td>
                        <td class="subject">
                            {{ $jurnal->materi }}
                        </td>
                        <td class="attendance">
                            <strong>{{ $jurnal->jml_hadir }} hadir</strong>
                            <br>
                            <small class="{{ $jurnal->jml_tidak_hadir > 0 ? 'red' : 'green' }}">
                                {{ $jurnal->jml_tidak_hadir }} tidak hadir
                            </small>
                        </td>
                        <td class="teacher">
                            {{ $jurnal->status_guru }}
                        </td>
                        <td>
                            @if($jurnal->status_validasi_guru === 'Disetujui' || $jurnal->status_validasi_guru === 'Valid')
                                <span class="status valid">
                                    <span class="material-symbols-outlined">check_circle</span>
                                    Disetujui
                                </span>
                            @elseif($jurnal->status_validasi_guru === 'Menunggu')
                                <span class="status waiting">
                                    <span class="material-symbols-outlined">schedule</span>
                                    Menunggu
                                </span>
                            @elseif($jurnal->status_validasi_guru === 'Ditolak')
                                <span class="status rejected">
                                    <span class="material-symbols-outlined">cancel</span>
                                    Ditolak
                                </span>
                            @else
                                <span class="status waiting">
                                    <span class="material-symbols-outlined">help</span>
                                    {{ $jurnal->status_validasi_guru }}
                                </span>
                            @endif
                        </td>
                        <td>
                            <button class="action">
                                <span class="material-symbols-outlined">visibility</span>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="empty" style="text-align: center;">
                            Belum ada data jurnal.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(method_exists($jurnalTerbaru, 'links'))
        <div class="bottom">
            <div class="entries">
                Showing {{ $jurnalTerbaru->firstItem() ?? 0 }} to {{ $jurnalTerbaru->lastItem() ?? 0 }} of {{ $jurnalTerbaru->total() ?? 0 }} entries
            </div>

            <div class="pagination">
                {{ $jurnalTerbaru->links() }}
            </div>
        </div>
    @endif
</section>

@endsection