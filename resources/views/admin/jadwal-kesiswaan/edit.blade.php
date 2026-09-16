@extends('layouts.admin')

@section('title', 'Edit Jadwal Kesiswaan - Jurnify')
@section('page-title', 'Edit Jadwal Kesiswaan')

@section('content')
<div class="card" style="max-width: 760px;">
    <h3 style="margin-bottom: 16px;">Edit Jadwal Petugas Kesiswaan</h3>
    <form action="{{ route('admin.jadwal-kesiswaan.update', $jadwal) }}" method="POST">
        @csrf
        @method('PUT')
        @include('admin.jadwal-kesiswaan.form')
        <div style="margin-top: 18px; display: flex; gap: 12px;">
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="{{ route('admin.jadwal-kesiswaan.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
