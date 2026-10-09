<div class="dispen-detail-content">
    <div class="dispen-detail-overview">
        <div class="dispen-detail-fact"><span class="dispen-detail-label">Tanggal</span><strong>{{ $dispen->tanggal?->translatedFormat('d M Y') ?? '-' }}</strong></div>
        <div class="dispen-detail-fact"><span class="dispen-detail-label">Waktu</span><strong>Jam {{ $dispen->jamMulai?->jam_ke ?? '-' }}–{{ $dispen->jamSelesai?->jam_ke ?? '-' }}</strong><small>{{ substr($dispen->jamMulai?->jam_mulai ?? '', 0, 5) }}–{{ substr($dispen->jamSelesai?->jam_selesai ?? '', 0, 5) }}</small></div>
        <div class="dispen-detail-fact"><span class="dispen-detail-label">Status</span><span class="dispen-detail-status dispen-detail-status--{{ $dispen->status }}">{{ ucfirst($dispen->status) }}</span></div>
    </div>

    <section class="dispen-detail-section">
        <div class="dispen-detail-section-heading"><h3>Daftar Siswa</h3><span>{{ $dispens->count() }} siswa</span></div>
        <div class="dispen-detail-students">
            @foreach($dispens as $item)
                <div class="dispen-detail-student"><span class="dispen-detail-student-number">{{ $loop->iteration }}</span><div><strong>{{ $item->siswa->nama_siswa ?? '-' }}</strong><small>{{ $item->siswa->kelas->nama_kelas ?? '-' }}</small></div></div>
            @endforeach
        </div>
    </section>

    <div class="dispen-detail-info-grid">
        <div class="dispen-detail-info"><span class="dispen-detail-label">Alasan</span><p>{{ $dispen->alasan ?: '-' }}</p></div>
        <div class="dispen-detail-info"><span class="dispen-detail-label">Waka Bertugas</span><p>{{ $petugas?->nama_guru ?? '-' }}</p></div>
        @if($dispen->approver || $dispen->disetujui_pada)
            <div class="dispen-detail-info"><span class="dispen-detail-label">Dikonfirmasi</span><p>{{ $dispen->approver?->nama_user ?? '-' }}@if($dispen->disetujui_pada)<small>{{ $dispen->disetujui_pada->translatedFormat('d M Y, H:i') }}</small>@endif</p></div>
        @endif
        @if($dispen->catatan_persetujuan)
            <div class="dispen-detail-info"><span class="dispen-detail-label">Catatan</span><p>{{ $dispen->catatan_persetujuan }}</p></div>
        @endif
    </div>

    @if($dispen->status === 'menunggu')
        <div class="dispen-detail-footer"><a href="{{ route('piket.dispen.whatsapp', $dispen->id_dispen) }}" target="_blank" rel="noopener"><span class="material-symbols-outlined">share</span>Bagikan ke WhatsApp</a></div>
    @endif
</div>
