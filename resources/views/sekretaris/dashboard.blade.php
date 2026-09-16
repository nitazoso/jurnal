@extends('layouts.sekretaris')
@section('title', 'Dashboard Sekretaris')
@section('header', 'Dashboard')
@section('content')
<div class="page-heading"><h2>Selamat datang, {{ auth()->user()->nama_user ?? 'Sekretaris' }}</h2><p>Ringkasan jurnal pembelajaran yang perlu Anda kelola.</p></div>
<div class="stats">
    <div class="stat-card"><span class="stat-symbol">▣</span><p>Menunggu Validasi</p><strong>{{ $jurnalsMenunggu }}</strong><span class="hint">Perlu ditinjau</span></div>
    <div class="stat-card"><span class="stat-symbol">✓</span><p>Tervalidasi Hari Ini</p><strong>{{ $jurnalsTervalidasiHariIni }}</strong><span class="hint">Sudah tersedia untuk staf piket</span></div>
    <div class="stat-card"><span class="stat-symbol">✎</span><p>Isi Jurnal Guru</p><strong>+</strong><span class="hint">Buat jurnal atas nama guru</span></div>
</div>
<div class="card"><div class="card-head"><div><h3>Jurnal Menunggu Validasi</h3><p>Jurnal yang dikirim guru dan membutuhkan keputusan Anda.</p></div><a class="button secondary" href="{{ route('sekretaris.validasi-jurnal') }}">Lihat semua</a></div>
    <div class="table-wrap"><table><thead><tr><th>Guru</th><th>Kelas / Jam</th><th>Materi</th><th>Tanggal</th><th>Status</th></tr></thead><tbody>
    @forelse($jurnalTerbaru as $jurnal)<tr><td><span class="teacher"><span class="teacher-dot">{{ strtoupper(substr($jurnal->guru->nama_guru ?? 'G', 0, 1)) }}</span>{{ $jurnal->guru->nama_guru ?? '-' }}</span></td><td>{{ $jurnal->kelas->nama_kelas ?? '-' }}<br><small>Jam ke-{{ $jurnal->jamMulai->jam_ke ?? '-' }} — {{ $jurnal->jamSelesai->jam_ke ?? '-' }}</small></td><td>{{ $jurnal->materi }}</td><td>{{ $jurnal->tanggal?->format('d M Y') }}</td><td><span class="badge pending">Menunggu</span></td></tr>
    @empty<tr><td colspan="5" style="text-align:center;color:#73809a;padding:25px">Tidak ada jurnal yang menunggu validasi.</td></tr>@endforelse
    </tbody></table></div>
</div>
@endsection
