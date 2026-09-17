@extends('layouts.admin')

@section('title', 'Tambah Jadwal Piket - Jurnify')
@section('page-title', 'Tambah Jadwal Piket')

@section('content')
<div class="card" style="max-width: 900px;">
    <h3 style="margin-bottom: 16px;">Buat Jadwal Piket</h3>
    <form action="{{ route('admin.jadwal-piket.store') }}" method="POST">
        @csrf
        @include('admin.jadwal-piket.form')
        <div style="margin-top: 18px; display: flex; gap: 12px;">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.jadwal-piket.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
