@extends('layouts.admin')

@section('title', 'Guru - Jurnify')
@section('page-title', 'Guru')

@section('content')

<h2>Data Guru</h2>

<p>
    Kelola data guru yang terdaftar di sistem Jurnify.
</p>

<hr>

@if (session('success'))
    <div style="
        background:#e7f6ec;
        color:#18794e;
        padding:12px 15px;
        margin-bottom:20px;
        border-radius:6px;
    ">
        {{ session('success') }}
    </div>
@endif


{{-- STATISTIK --}}

<h3>Statistik</h3>

<div style="margin-bottom:25px;">

    <strong>Total Guru</strong>

    <p>
        {{ $totalGuru }}
    </p>

</div>


<hr>


{{-- DATA GURU --}}

<div style="
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
">

    <h3>Daftar Guru</h3>

    <a href="{{ route('admin.guru.create') }}">
        + Tambah Guru
    </a>

</div>


{{-- SEARCH --}}

<form
    action="{{ route('admin.guru.index') }}"
    method="GET"
    style="margin-bottom:20px;"
>

    <input
        type="text"
        name="search"
        value="{{ request('search') }}"
        placeholder="Cari NIP atau nama guru..."
    >

    <button type="submit">
        Cari
    </button>

    <a href="{{ route('admin.guru.index') }}">
        Reset
    </a>

</form>


{{-- TABLE --}}

<table
    border="1"
    cellpadding="10"
    cellspacing="0"
    width="100%"
>

    <thead>

        <tr>

            <th>No</th>

            <th>NIP</th>

            <th>Nama Guru</th>

            <th>No. HP</th>

            <th>Aksi</th>

        </tr>

    </thead>


    <tbody>

        @forelse ($gurus as $index => $guru)

            <tr>

                <td>
                    {{ $gurus->firstItem() + $index }}
                </td>

                <td>
                    {{ $guru->nip }}
                </td>

                <td>
                    {{ $guru->nama_guru }}
                </td>

                <td>
                    {{ $guru->no_hp ?? '-' }}
                </td>

                <td>

                    <a
                        href="{{ route('admin.guru.edit', $guru->id_guru) }}"
                    >
                        Edit
                    </a>


                    <form
                        action="{{ route('admin.guru.destroy', $guru->id_guru) }}"
                        method="POST"
                        style="display:inline;"
                        onsubmit="return confirm('Yakin ingin menghapus guru ini?')"
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

                <td
                    colspan="5"
                    style="text-align:center;"
                >
                    Belum ada data guru.
                </td>

            </tr>

        @endforelse

    </tbody>

</table>


<br>

{{ $gurus->links() }}

@endsection