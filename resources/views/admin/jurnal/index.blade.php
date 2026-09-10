@extends('layouts.admin')

@section('title', 'Daftar Jurnal - Jurnify')

@section('page-title', 'Daftar Jurnal')

@section('content')

<div class="activity-header">
    <h3 class="activity-title">
        Daftar Jurnal
    </h3>

    <p class="activity-description">
        Daftar jurnal pembelajaran yang telah dibuat oleh guru.
    </p>
</div>

<div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th>NO</th>
                <th>TANGGAL</th>
                <th>GURU</th>
                <th>MAPEL</th>
                <th>KELAS</th>
                <th>MATERI</th>
                <th>KEHADIRAN</th>
                <th>STATUS GURU</th>
                <th>VALIDASI</th>
            </tr>
        </thead>

        <tbody>
            @forelse($jurnals as $jurnal)
                <tr>
                    <td>
                        {{ $jurnals->firstItem() + $loop->index }}
                    </td>

                    <td>
                        {{ $jurnal->tanggal->format('d M Y') }}
                    </td>

                    <td>
                        {{ $jurnal->guru->nama_guru ?? '-' }}
                    </td>

                    <td>
                        {{ $jurnal->jadwal->mapel->nama_mapel ?? '-' }}
                    </td>

                    <td>
                        {{ $jurnal->kelas->nama_kelas ?? '-' }}
                    </td>

                    <td>
                        {{ $jurnal->materi }}
                    </td>

                    <td>
                        {{ $jurnal->jml_hadir }} hadir
                        <br>
                        <small>
                            {{ $jurnal->jml_tidak_hadir }} tidak hadir
                        </small>
                    </td>

                    <td>
                        {{ $jurnal->status_guru }}
                    </td>

                    <td>
                        {{ $jurnal->status_validasi_guru }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="empty">
                        Belum ada data jurnal.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($jurnals->hasPages())
    <div style="margin-top: 20px;">
        {{ $jurnals->links() }}
    </div>
@endif

@endsection