@extends('layouts.kesiswaan')

@section('title', 'Dashboard Kesiswaan - Jurnify')
@section('page-title', 'Dashboard Kesiswaan')

@section('content')
<div class="card">
    <h3>Selamat datang, {{ auth()->user()->nama_user ?? '-' }}</h3>
    <p>Pengajuan menunggu: {{ $dispensMenunggu }}</p>
    <p><a href="{{ route('kesiswaan.dispen.index') }}">Buka semua pengajuan dispen</a></p>

    <h3>Notifikasi</h3>
    @forelse ($notifikasi as $notification)
        <p>
            <a href="{{ $notification->data['url'] }}">
                {{ $notification->data['message'] }}
            </a>
            @if (!empty($notification->data['whatsapp_url']))
                | <a href="{{ $notification->data['whatsapp_url'] }}" target="_blank" rel="noopener">Buka WhatsApp</a>
            @endif
        </p>
    @empty
        <p>Tidak ada notifikasi baru.</p>
    @endforelse
</div>
@endsection
