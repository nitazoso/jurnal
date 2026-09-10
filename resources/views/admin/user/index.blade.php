@extends('layouts.admin')

@section('title', 'Manajemen Akun - Jurnify')
@section('page-title', 'Manajemen Akun')

@section('content')

<h2>Manajemen Akun</h2>

<p>Kelola akun pengguna Jurnify.</p>

<hr>

<h3>Statistik</h3>

<div>
    <div>
        <strong>Total User</strong>
        <p>{{ $totalUser }}</p>
    </div>

    <div>
        <strong>Guru</strong>
        <p>{{ $totalGuru }}</p>
    </div>

    <div>
        <strong>Staff Piket</strong>
        <p>{{ $totalStaffPiket }}</p>
    </div>

    <div>
        <strong>Sekretaris</strong>
        <p>{{ $totalSekretaris }}</p>
    </div>

    <div>
        <strong>Siswa</strong>
        <p>{{ $totalSiswa }}</p>
    </div>
</div>

<hr>

<h3>Data User</h3>

<a href="{{ route('admin.user.create') }}">
    + Tambah User
</a>

<br><br>

<form action="{{ route('admin.user.index') }}" method="GET">

    <input
        type="text"
        name="search"
        placeholder="Cari username atau nama..."
        value="{{ request('search') }}"
    >

    <select name="role">

        <option value="">Semua Role</option>

        <option value="Admin" {{ request('role') == 'Admin' ? 'selected' : '' }}>
            Admin
        </option>

        <option value="Guru" {{ request('role') == 'Guru' ? 'selected' : '' }}>
            Guru
        </option>

        <option value="Sekretaris" {{ request('role') == 'Sekretaris' ? 'selected' : '' }}>
            Sekretaris
        </option>

        <option value="Staff Piket" {{ request('role') == 'Staff Piket' ? 'selected' : '' }}>
            Staff Piket
        </option>

    </select>

    <button type="submit">
        Cari
    </button>

    <a href="{{ route('admin.user.index') }}">
        Reset
    </a>

</form>

<br>

<table border="1" cellpadding="10" cellspacing="0">

    <thead>
        <tr>
            <th>No</th>
            <th>Username</th>
            <th>Nama</th>
            <th>Role</th>
            <th>Guru</th>
            <th>Kelas</th>
            <th>Aksi</th>
        </tr>
    </thead>

    <tbody>

        @forelse($users as $index => $user)

            <tr>

                <td>
                    {{ $users->firstItem() + $index }}
                </td>

                <td>
                    {{ $user->username }}
                </td>

                <td>
                    {{ $user->nama_user }}
                </td>

                <td>
                    {{ $user->role }}
                </td>

                <td>
                    {{ $user->guru->nama_guru ?? '-' }}
                </td>

                <td>
                    {{ $user->kelas->nama_kelas ?? '-' }}
                </td>

                <td>

                    <a href="{{ route('admin.user.edit', $user->id_user) }}">
                        Edit
                    </a>

                    <form
                        action="{{ route('admin.user.destroy', $user->id_user) }}"
                        method="POST"
                        style="display:inline;"
                        onsubmit="return confirm('Yakin ingin menghapus user ini?')"
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
                <td colspan="7">
                    Belum ada data user.
                </td>
            </tr>

        @endforelse

    </tbody>

</table>

<br>

{{ $users->links() }}

@endsection