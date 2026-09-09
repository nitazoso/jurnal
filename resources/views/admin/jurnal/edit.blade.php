@extends('layouts.admin')

@section('title', 'Edit Jurnal - Jurnify')

@section('page-title', 'Edit Jurnal')

@section('content')

<div class="activity-header">
    <h3 class="activity-title">Edit Jurnal</h3>

    <p class="activity-description">
        Perbarui data jurnal pembelajaran.
    </p>
</div>

<form action="{{ route('admin.jurnal.update', $jurnal->id_jurnal) }}" method="POST">
    @csrf
    @method('PUT')

    <div>
        <label>Tanggal</label>
        <input
            type="date"
            name="tanggal"
            value="{{ $jurnal->tanggal->format('Y-m-d') }}"
            required
        >
    </div>

    <div>
        <label>Jadwal</label>
        <select name="id_jadwal" required>
            @foreach($jadwals as $jadwal)
                <option
                    value="{{ $jadwal->id_jadwal }}"
                    {{ $jurnal->id_jadwal == $jadwal->id_jadwal ? 'selected' : '' }}
                >
                    {{ $jadwal->hari }} - {{ $jadwal->tahun_ajaran }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label>Guru</label>
        <select name="id_guru" required>
            @foreach($guru as $item)
                <option
                    value="{{ $item->id_guru }}"
                    {{ $jurnal->id_guru == $item->id_guru ? 'selected' : '' }}
                >
                    {{ $item->nama_guru }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label>Kelas</label>
        <select name="id_kelas" required>
            @foreach($kelas as $item)
                <option
                    value="{{ $item->id_kelas }}"
                    {{ $jurnal->id_kelas == $item->id_kelas ? 'selected' : '' }}
                >
                    {{ $item->nama_kelas }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label>Jam Mulai</label>
        <select name="id_jam_mulai" required>
            @foreach($jamPels as $jam)
                <option
                    value="{{ $jam->id_jam }}"
                    {{ $jurnal->id_jam_mulai == $jam->id_jam ? 'selected' : '' }}
                >
                    Jam {{ $jam->jam_ke }} ({{ $jam->jam_mulai }})
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label>Jam Selesai</label>
        <select name="id_jam_selesai" required>
            @foreach($jamPels as $jam)
                <option
                    value="{{ $jam->id_jam }}"
                    {{ $jurnal->id_jam_selesai == $jam->id_jam ? 'selected' : '' }}
                >
                    Jam {{ $jam->jam_ke }} ({{ $jam->jam_selesai }})
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label>Materi</label>
        <input
            type="text"
            name="materi"
            value="{{ $jurnal->materi }}"
            required
        >
    </div>

    <div>
        <label>Status Guru</label>
        <select name="status_guru" required>
            @foreach(['Hadir', 'Izin', 'Sakit', 'Tanpa Keterangan'] as $status)
                <option
                    value="{{ $status }}"
                    {{ $jurnal->status_guru == $status ? 'selected' : '' }}
                >
                    {{ $status }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label>Jumlah Hadir</label>
        <input
            type="number"
            name="jml_hadir"
            value="{{ $jurnal->jml_hadir }}"
            min="0"
            required
        >
    </div>

    <div>
        <label>Jumlah Tidak Hadir</label>
        <input
            type="number"
            name="jml_tidak_hadir"
            value="{{ $jurnal->jml_tidak_hadir }}"
            min="0"
            required
        >
    </div>

    <div>
        <label>Ada Tugas?</label>
        <select name="ada_tugas" required>
            <option value="Tidak" {{ $jurnal->ada_tugas == 'Tidak' ? 'selected' : '' }}>
                Tidak
            </option>

            <option value="Ya" {{ $jurnal->ada_tugas == 'Ya' ? 'selected' : '' }}>
                Ya
            </option>
        </select>
    </div>

    <div>
        <label>Deskripsi Tugas</label>
        <textarea name="deskripsi_tugas">{{ $jurnal->deskripsi_tugas }}</textarea>
    </div>

    <div>
        <label>Catatan Umum</label>
        <input
            type="text"
            name="catatan_umum"
            value="{{ $jurnal->catatan_umum }}"
        >
    </div>

    <button type="submit">
        Update Jurnal
    </button>
</form>

@endsection