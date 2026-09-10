```blade
@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')

<section class="stats">

    {{-- JURNAL HARI INI --}}
    <div class="stat-card">
        <div class="stat-title">Aktivitas Jurnal Hari Ini</div>

        <div class="stat-value">
            <strong>{{ $jurnalHariIni }}</strong>
            <span>Terisi</span>
        </div>

        <span class="today-badge">Hari Ini</span>
    </div>


    {{-- JUMLAH GURU --}}
    <div class="stat-card">
        <div class="stat-title">Jumlah Guru</div>

        <div class="stat-value">
            <strong>{{ $jumlahGuru }}</strong>
            <span>Terdaftar</span>
        </div>

        <div class="stat-icon">
            <span class="material-symbols-outlined">
                person_add
            </span>
        </div>
    </div>


    {{-- JUMLAH KELAS --}}
    <div class="stat-card">
        <div class="stat-title">Jumlah Kelas</div>

        <div class="stat-value">
            <strong>{{ $jumlahKelas }}</strong>
            <span>Kelas</span>
        </div>

        <div class="stat-icon">
            <span class="material-symbols-outlined">
                meeting_room
            </span>
        </div>
    </div>

</section>


{{-- ===================================================== --}}
{{-- AKTIVITAS JURNAL --}}
{{-- ===================================================== --}}

<section class="activity-card">

    <div class="activity-header">
        <h3 class="activity-title">
            Aktivitas Jurnal Terkini
        </h3>

        <p class="activity-description">
            Daftar entri jurnal pembelajaran harian dan status validasi kurikulum.
        </p>
    </div>


    {{-- FILTER --}}
    <div class="filters">

        <div class="search-box">
            <span class="material-symbols-outlined">
                search
            </span>

            <input
                type="text"
                placeholder="Cari jurnal, nama guru, kelas, mapel..."
            >
        </div>


        <select class="filter-select">
            <option value="">Semua Status Validasi</option>
            <option value="Valid">Valid</option>
            <option value="Menunggu">Menunggu</option>
            <option value="Ditolak">Ditolak</option>
        </select>


        <select class="filter-select">
            <option value="">Semua Kelas</option>

            @foreach($jurnalTerbaru->pluck('kelas')->filter()->unique('id_kelas') as $kelas)
                <option value="{{ $kelas->id_kelas }}">
                    {{ $kelas->nama_kelas }}
                </option>
            @endforeach
        </select>


        <select class="filter-select">
            <option value="">Semua Tanggal</option>

            @foreach($jurnalTerbaru->pluck('tanggal')->filter()->unique() as $tanggal)
                <option value="{{ $tanggal->format('Y-m-d') }}">
                    {{ $tanggal->format('d M Y') }}
                </option>
            @endforeach
        </select>

    </div>


    {{-- TABLE --}}
    <div class="table-wrapper">

        <table>

            <thead>
                <tr>
                    <th>NO</th>

                    <th>
                        TANGGAL &<br>
                        JAM
                    </th>

                    <th>GURU</th>

                    <th>
                        MATA<br>
                        PELAJARAN
                    </th>

                    <th>KELAS</th>

                    <th>KEHADIRAN</th>

                    <th>
                        STATUS<br>
                        VALIDASI
                    </th>

                    <th>AKSI</th>
                </tr>
            </thead>


            <tbody>

                @forelse($jurnalTerbaru as $index => $jurnal)

                    <tr>

                        {{-- NOMOR --}}
                        <td class="number">
                            {{ $index + 1 }}
                        </td>


                        {{-- TANGGAL & JAM --}}
                        <td>

                            <span class="date">
                                {{ $jurnal->tanggal?->format('d M Y') ?? '-' }}
                            </span>

                            <span class="time">

                                @if($jurnal->jamMulai && $jurnal->jamSelesai)

                                    Jam Ke
                                    {{ $jurnal->jamMulai->jam_ke }}
                                    -
                                    {{ $jurnal->jamSelesai->jam_ke }}

                                @else

                                    -

                                @endif

                            </span>

                        </td>


                        {{-- GURU --}}
                        <td class="teacher">

                            @if($jurnal->guru)

                                {{ $jurnal->guru->nama_guru }}

                            @else

                                -

                            @endif

                        </td>


                        {{-- MAPEL --}}
                        <td class="subject">

                            @if($jurnal->mapel)

                                {{ $jurnal->mapel->nama_mapel }}

                            @else

                                -

                            @endif

                        </td>


                        {{-- KELAS --}}
                        <td>

                            @if($jurnal->kelas)

                                <span class="class-badge">
                                    {{ $jurnal->kelas->nama_kelas }}
                                </span>

                            @else

                                -

                            @endif

                        </td>


                        {{-- KEHADIRAN --}}
                        <td class="attendance">

                            <strong>
                                {{ $jurnal->jml_hadir ?? 0 }}
                            </strong>

                            <small>
                                Hadir
                            </small>

                            @if(($jurnal->jml_tidak_hadir ?? 0) > 0)

                                <small class="red">
                                    {{ $jurnal->jml_tidak_hadir }}
                                    Tidak Hadir
                                </small>

                            @endif

                        </td>


                        {{-- STATUS --}}
                        <td>

                            @php
                                $status = $jurnal->status_validasi_guru ?? 'Menunggu';
                            @endphp


                            @if($status === 'Valid')

                                <span class="status valid">

                                    <span class="material-symbols-outlined">
                                        check_circle
                                    </span>

                                    Valid

                                </span>


                            @elseif($status === 'Ditolak')

                                <span class="status rejected">

                                    <span class="material-symbols-outlined">
                                        cancel
                                    </span>

                                    Ditolak

                                </span>


                            @else

                                <span class="status waiting">

                                    <span class="material-symbols-outlined">
                                        schedule
                                    </span>

                                    Menunggu

                                </span>

                            @endif

                        </td>


                        {{-- AKSI --}}
                        <td>

                            <a
                                href="{{ route('admin.jurnal.index') }}"
                                class="action"
                                title="Lihat jurnal"
                            >

                                <span class="material-symbols-outlined">
                                    visibility
                                </span>

                            </a>

                        </td>

                    </tr>


                @empty

                    <tr>

                        <td
                            colspan="8"
                            style="text-align: center; padding: 30px;"
                        >

                            Belum ada data jurnal.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    {{-- BOTTOM --}}
    <div class="bottom">

        <div class="entries">

            Menampilkan
            {{ $jurnalTerbaru->count() }}
            jurnal terbaru

        </div>

        <div class="pagination">

            <span class="dots">
                Dashboard
            </span>

        </div>

    </div>

</section>

@endsection
```
