@extends('layouts.piket')

@section('title', 'Ringkasan Dispen - Jurnify')
@section('page-title', 'Ringkasan Dispen')

@push('styles')
<style>
    .summary-page{--summary-navy:#30366f;--summary-ink:#202747;--summary-muted:#778097;display:grid;gap:20px;margin:0 auto;max-width:940px;padding:clamp(16px,3vw,32px)}
    .summary-success{display:flex;align-items:center;gap:14px;padding:18px 20px;border:1px solid #ccebd8;border-radius:14px;background:linear-gradient(110deg,#f0fbf4,#fff);color:#236c48}
    .summary-success-icon{display:grid;width:42px;height:42px;flex:0 0 42px;place-items:center;border-radius:50%;background:#daf4e4}.summary-success h1{margin:0;color:#1d6945;font-size:18px;font-weight:800}.summary-success p{margin:4px 0 0;color:#548166;font-size:12px}
    .summary-card{overflow:hidden;border:1px solid #e7eaf2;border-radius:17px;background:#fff;box-shadow:0 10px 28px rgba(25,35,75,.05)}.summary-head{padding:22px 24px;border-bottom:1px solid #edf0f5}.summary-eyebrow{color:#727ba9;font-size:10px;font-weight:800;letter-spacing:.12em;text-transform:uppercase}.summary-head h2{margin:6px 0 0;color:var(--summary-ink);font-size:20px;font-weight:800}
    .summary-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:0;padding:4px 24px}.summary-item{min-width:0;padding:17px 0;border-bottom:1px solid #f0f2f7}.summary-item:nth-last-child(-n+2){border-bottom:0}.summary-label{display:block;margin-bottom:6px;color:#8a91a4;font-size:10px;font-weight:800;letter-spacing:.06em;text-transform:uppercase}.summary-value{color:#3a425b;font-size:13px;font-weight:700;line-height:1.6;overflow-wrap:anywhere}.summary-reason{grid-column:1/-1}.summary-status{display:inline-flex;align-items:center;gap:5px;border-radius:999px;background:#fff5e4;padding:5px 10px;color:#9f6c1f;font-size:11px;font-weight:800}
    .summary-link-box{display:grid;gap:8px;margin:0 24px 22px;padding:16px;border:1px solid #e0e8ff;border-radius:12px;background:#f7f9ff}.summary-link-label{color:#5a648d;font-size:11px;font-weight:800}.summary-link-row{display:flex;align-items:center;gap:8px;min-width:0}.summary-verification-link{min-width:0;overflow-wrap:anywhere;color:#2457cb;font-size:12px;font-weight:700;text-decoration:underline;text-decoration-thickness:1px;text-underline-offset:3px}.summary-verification-link:hover{color:#173d9a}.summary-copy{display:inline-flex;flex:0 0 auto;align-items:center;gap:5px;padding:7px 10px;border:1px solid #d5def5;border-radius:8px;background:#fff;color:#3f5599;font:inherit;font-size:10px;font-weight:800;cursor:pointer}.summary-copy:hover{background:#edf2ff}
    .summary-actions{display:flex;flex-wrap:wrap;gap:10px;padding:0 24px 24px}.summary-button{display:inline-flex;min-height:44px;align-items:center;justify-content:center;gap:8px;padding:11px 16px;border:1px solid transparent;border-radius:10px;font:inherit;font-size:12px;font-weight:800;text-decoration:none;transition:transform .16s,box-shadow .16s,background .16s}.summary-button:hover{transform:translateY(-1px);box-shadow:0 7px 16px rgba(20,40,40,.12)}.summary-share{background:#168b55;color:#fff}.summary-share:hover{background:#117446}.summary-back{border-color:#e0e4ed;background:#fff;color:#555f78}.summary-back:hover{background:#f7f8fb}
    .summary-note{margin:0 24px 18px;color:var(--summary-muted);font-size:11px;line-height:1.6}
    @media(max-width:600px){.summary-page{gap:14px;padding:14px}.summary-success{align-items:flex-start;padding:15px}.summary-success h1{font-size:16px}.summary-card{border-radius:13px}.summary-head{padding:18px}.summary-grid{grid-template-columns:1fr;padding:0 18px}.summary-item,.summary-item:nth-last-child(-n+2){border-bottom:1px solid #f0f2f7;padding:13px 0}.summary-item:last-child{border-bottom:0}.summary-reason{grid-column:auto}.summary-link-box{margin:0 18px 18px;padding:13px}.summary-link-row{align-items:flex-start;flex-direction:column}.summary-actions{display:grid;grid-template-columns:1fr;padding:0 18px 18px}.summary-button{width:100%}.summary-note{margin:0 18px 16px}}
</style>
@endpush

@section('content')
<div class="summary-page">
    <div class="summary-success" role="status"><span class="summary-success-icon material-symbols-outlined">check_circle</span><div><h1>Dispen berhasil disimpan</h1><p>Periksa ringkasan berikut, lalu bagikan permohonan ke Waka yang bertugas.</p></div></div>
    <section class="summary-card">
        <header class="summary-head"><span class="summary-eyebrow">Permohonan baru</span><h2>Ringkasan Dispen</h2></header>
        <div class="summary-grid">
            <div class="summary-item"><span class="summary-label">Nama siswa</span><div class="summary-value">{{ $dispen->siswa->nama_siswa ?? '-' }}</div></div>
            <div class="summary-item"><span class="summary-label">Kelas</span><div class="summary-value">{{ $dispen->siswa->kelas->nama_kelas ?? '-' }}</div></div>
            <div class="summary-item"><span class="summary-label">Tanggal</span><div class="summary-value">{{ $dispen->tanggal?->translatedFormat('l, d F Y') ?? '-' }}</div></div>
            <div class="summary-item"><span class="summary-label">Jam</span><div class="summary-value">Jam ke-{{ $dispen->jamMulai->jam_ke ?? '?' }} sampai ke-{{ $dispen->jamSelesai->jam_ke ?? '?' }}<br>{{ substr($dispen->jamMulai->jam_mulai ?? '', 0, 5) }}–{{ substr($dispen->jamSelesai->jam_selesai ?? '', 0, 5) }}</div></div>
            <div class="summary-item"><span class="summary-label">Waka bertugas</span><div class="summary-value">{{ $petugas?->nama_guru ?? 'Belum ditemukan' }}@if($petugas?->no_hp)<span style="display:block;color:#7c8499;font-size:11px;font-weight:500">{{ $petugas->no_hp }}</span>@endif</div></div>
            <div class="summary-item"><span class="summary-label">Status</span><div class="summary-value"><span class="summary-status"><span class="material-symbols-outlined" style="font-size:14px">schedule</span>Menunggu persetujuan</span></div></div>
            <div class="summary-item summary-reason"><span class="summary-label">Alasan</span><div class="summary-value">{{ $dispen->alasan }}</div></div>
        </div>
        <!-- <div class="summary-link-box"><span class="summary-link-label">Tautan verifikasi Waka</span><div class="summary-link-row"><a class="summary-verification-link" href="{{ $verificationUrl }}" target="_blank" rel="noopener">{{ $verificationUrl }}</a><button class="summary-copy" type="button" data-copy-link="{{ $verificationUrl }}"><span class="material-symbols-outlined" style="font-size:15px">content_copy</span>Salin tautan</button></div></div> -->
        <p class="summary-note">Pesan WhatsApp akan berisi data siswa, jadwal, alasan, dan tautan verifikasi ini.</p>
        <div class="summary-actions"><a class="summary-button summary-share" href="{{ route('piket.dispen.whatsapp', $dispen->id_dispen) }}" target="_blank" rel="noopener"><span class="material-symbols-outlined" style="font-size:19px">share</span>Bagikan ke WhatsApp</a><a class="summary-button summary-back" href="{{ route('piket.dispen.index') }}"><span class="material-symbols-outlined" style="font-size:18px">list_alt</span>Kembali ke daftar dispen</a></div>
    </section>
</div>
<script>
    document.querySelector('[data-copy-link]')?.addEventListener('click', async (event) => {
        const button = event.currentTarget;
        try {
            await navigator.clipboard.writeText(button.dataset.copyLink);
            button.innerHTML = '<span class="material-symbols-outlined" style="font-size:15px">check</span>Tersalin';
        } catch {
            window.prompt('Salin tautan verifikasi:', button.dataset.copyLink);
        }
    });
</script>
@endsection
