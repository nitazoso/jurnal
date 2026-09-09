@extends('layouts.admin')

@section('title', 'Daftar Jurnal - Jurnify')

@section('page-title', 'Daftar Jurnal')

@section('content')

<div class="activity-header">
    <h3 class="activity-title">
        Daftar Jurnal
    </h3>

    <p class="activity-description">
        Daftar jurnal pembelajaran yang telah dibuat.
    </p>
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
            @forelse($jurnals as $jurnal)
                <tr>
                    <td>{{ $jurnals->firstItem() + $loop->index }}</td>

                    <td>
                        {{ $jurnal->tanggal->format('d M Y') }}
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
                    <td>
    <a href="{{ route('admin.jurnal.edit', $jurnal->id_jurnal) }}">
        Edit
    </a>

    <form
        action="{{ route('admin.jurnal.destroy', $jurnal->id_jurnal) }}"
        method="POST"
        style="display: inline;"
    >
        @csrf
        @method('DELETE')

        <button type="submit">
            Hapus
        </button>
    </form>
</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="empty">
                        Belum ada data jurnal.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
