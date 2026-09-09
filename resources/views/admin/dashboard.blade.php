@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<section class="stats">
    <div class="stat-card">
        <div class="stat-title">Aktivitas Jurnal Hari Ini</div>
        <div class="stat-value">
            <strong>28</strong>
            <span>Terisi</span>
        </div>
        <span class="today-badge">Hari Ini</span>
    </div>

    <div class="stat-card">
        <div class="stat-title">Jumlah Guru</div>
        <div class="stat-value">
            <strong>42</strong>
            <span>Terdaftar</span>
        </div>
        <div class="stat-icon">
            <span class="material-symbols-outlined">person_add</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-title">Jumlah Kelas</div>
        <div class="stat-value">
            <strong>18</strong>
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

    <div class="filters">
        <div class="search-box">
            <span class="material-symbols-outlined">search</span>
            <input type="text" placeholder="Cari jurnal, nama guru, kelas, mapel...">
        </div>

        <select class="filter-select">
            <option>Semua Status Validasi</option>
            <option>Valid</option>
            <option>Menunggu</option>
            <option>Ditolak</option>
        </select>

        <select class="filter-select">
            <option>Semua Kelas</option>
            <option>XII IPA 1</option>
            <option>XII IPA 2</option>
        </select>

        <select class="filter-select">
            <option>Semua Tanggal</option>
            <option>12 Okt 2023</option>
            <option>11 Okt 2023</option>
        </select>
    </div>

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
                <tr>
                    <td class="number">1</td>
                    <td>
                        <span class="date">12 Okt 2023</span>
                        <span class="time">Jam Ke 1-2</span>
                    </td>
                    <td class="teacher">Budi Santoso,<br>S.Pd</td>
                    <td class="subject">Matematika<br>Wajib</td>
                    <td><span class="class-badge">XII IPA 1</span></td>
                    <td class="attendance">
                        <strong>34/36</strong>
                        <small>2 Izin</small>
                    </td>
                    <td>
                        <span class="status valid">
                            <span class="material-symbols-outlined">check_circle</span>
                            Valid
                        </span>
                    </td>
                    <td>
                        <button class="action">
                            <span class="material-symbols-outlined">visibility</span>
                        </button>
                    </td>
                </tr>

                <tr>
                    <td class="number">2</td>
                    <td>
                        <span class="date">12 Okt 2023</span>
                        <span class="time">Jam Ke 3-4</span>
                    </td>
                    <td class="teacher">Siti Aminah,<br>M.Pd</td>
                    <td class="subject">Bahasa<br>Indonesia</td>
                    <td><span class="class-badge">XII IPA 1</span></td>
                    <td class="attendance">
                        <strong>28/32</strong>
                        <small class="red">2 Sakit, 2 Alpa</small>
                    </td>
                    <td>
                        <span class="status waiting">
                            <span class="material-symbols-outlined">schedule</span>
                            Menunggu
                        </span>
                    </td>
                    <td>
                        <button class="action">
                            <span class="material-symbols-outlined">visibility</span>
                        </button>
                    </td>
                </tr>

                <tr>
                    <td class="number">3</td>
                    <td>
                        <span class="date">12 Okt 2023</span>
                        <span class="time">Jam Ke 3-5</span>
                    </td>
                    <td class="teacher">Agus Wijaya,<br>S.Kom</td>
                    <td class="subject">Informatika</td>
                    <td><span class="class-badge">XII IPA 1</span></td>
                    <td class="attendance">
                        <strong>36/36</strong>
                        <small class="green">Hadir Semua</small>
                    </td>
                    <td>
                        <span class="status valid">
                            <span class="material-symbols-outlined">check_circle</span>
                            Valid
                        </span>
                    </td>
                    <td>
                        <button class="action">
                            <span class="material-symbols-outlined">visibility</span>
                        </button>
                    </td>
                </tr>

                <tr>
                    <td class="number">4</td>
                    <td>
                        <span class="date">11 Okt 2023</span>
                        <span class="time">Jam Ke 5-6</span>
                    </td>
                    <td class="teacher">Drs. Herman<br>Setiawan</td>
                    <td class="subject">Fisika</td>
                    <td><span class="class-badge">XII IPA 1</span></td>
                    <td class="attendance">
                        <strong>33/35</strong>
                        <small>1 Izin, 1 Sakit</small>
                    </td>
                    <td>
                        <span class="status valid">
                            <span class="material-symbols-outlined">check_circle</span>
                            Valid
                        </span>
                    </td>
                    <td>
                        <button class="action">
                            <span class="material-symbols-outlined">visibility</span>
                        </button>
                    </td>
                </tr>

                <tr>
                    <td class="number">5</td>
                    <td>
                        <span class="date">11 Okt 2023</span>
                        <span class="time">Jam Ke 7-8</span>
                    </td>
                    <td class="teacher">Dewi Lestari,<br>S.Pd</td>
                    <td class="subject">Kimia Organik</td>
                    <td><span class="class-badge">XII IPA 1</span></td>
                    <td class="attendance">
                        <strong>30/36</strong>
                        <small class="red">4 Alpa, 2 Izin</small>
                    </td>
                    <td>
                        <span class="status rejected">
                            <span class="material-symbols-outlined">cancel</span>
                            Ditolak
                        </span>
                    </td>
                    <td>
                        <button class="action">
                            <span class="material-symbols-outlined">visibility</span>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="bottom">
        <div class="entries">Showing 1 to 10 of 45 entries</div>

        <div class="pagination">
            <button class="page">
                <span class="material-symbols-outlined">chevron_left</span>
            </button>
            <button class="page active">1</button>
            <button class="page">2</button>
            <button class="page">3</button>
            <span class="dots">...</span>
            <button class="page">5</button>
            <button class="page">
                <span class="material-symbols-outlined">chevron_right</span>
            </button>
        </div>
    </div>
</section>
@endsection