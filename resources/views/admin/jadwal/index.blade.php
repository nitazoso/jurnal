@extends('layouts.admin')

@section('title', 'Jadwal - Jurnify')

@section('page-title', 'Jadwal')

@section('content')

<div class="activity-header">
    <div>
        <h3 class="activity-title">
            Jadwal Mengajar
        </h3>

```
    <p class="activity-description">
        Daftar jadwal mengajar guru.
    </p>
</div>

<a href="{{ route('admin.jadwal.create') }}"
   class="btn btn-primary">
    + Tambah Jadwal
</a>
```

</div>

@if(session('success')) <div class="alert alert-success">
{{ session('success') }} </div>
@endif

<div class="table-wrapper">

```
<table>

    <thead>
        <tr>
            <th>NO</th>
            <th>HARI</th>
            <th>GURU</th>
            <th>MAPEL</th>
            <th>KELAS</th>
            <th>JAM</th>
            <th>SEMESTER</th>
            <th>TAHUN AJARAN</th>
            <th>AKSI</th>
        </tr>
    </thead>

    <tbody>

        @forelse($jadwals as $jadwal)

            <tr>

                <td>
                    {{ $loop->iteration }}
                </td>

                <td>
                    {{ $jadwal->hari }}
                </td>

                <td>
                    {{ $jadwal->guru->nama_guru ?? '-' }}
                </td>

                <td>
                    {{ $jadwal->mapel->nama_mapel ?? '-' }}
                </td>

                <td>
                    {{ $jadwal->kelas->nama_kelas ?? '-' }}
                </td>

                <td>
                    {{ $jadwal->jamMulai->jam_mulai ?? '-' }}
                    -
                    {{ $jadwal->jamSelesai->jam_selesai ?? '-' }}
                </td>

                <td>
                    {{ $jadwal->semester }}
                </td>

                <td>
                    {{ $jadwal->tahun_ajaran }}
                </td>

                <td>

                    <a href="{{ route('admin.jadwal.edit', $jadwal->id_jadwal) }}"
                       class="btn btn-warning">
                        Edit
                    </a>

                    <form action="{{ route('admin.jadwal.destroy', $jadwal->id_jadwal) }}"
                          method="POST"
                          style="display: inline;"
                          onsubmit="return confirm('Yakin ingin menghapus jadwal ini?')">

                        @csrf
                        @method('DELETE')

                        <button type="submit"
                                class="btn btn-danger">
                            Hapus
                        </button>

                    </form>

                </td>

            </tr>

        @empty

            <tr>
                <td colspan="9" class="empty">
                    Belum ada data jadwal.
                </td>
            </tr>

        @endforelse

    </tbody>

</table>
```

</div>

@endsection
