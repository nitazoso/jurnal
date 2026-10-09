<div class="rekap-table-scroll rekap-journal-desktop">
    <table class="rekap-table">
        <thead><tr><th>No</th><th>Tanggal / Jam</th>@if($jenis === 'kelas')<th>Guru</th>@else<th>Kelas</th>@endif<th>Mapel</th><th>Materi</th><th>Hadir</th><th>Tidak Hadir</th><th>Status</th><th></th></tr></thead>
        <tbody>
        @forelse($jurnals as $jurnal)
            <tr class="rekap-click-row" role="link" tabindex="0" onclick="if (!event.target.closest('a')) window.location.href='{{ route('piket.jurnal.show', $jurnal) }}'" onkeydown="if (event.key === 'Enter' || event.key === ' ') { event.preventDefault(); window.location.href='{{ route('piket.jurnal.show', $jurnal) }}'; }">
                <td data-label="No">{{ $loop->iteration }}</td>
                <td data-label="Tanggal / Jam" class="rekap-date-cell">{{ $jurnal->tanggal?->format('d M Y') ?? '-' }}<span class="rekap-subtext">{{ $jurnal->jamMulai?->jam_ke ?? '-' }}{{ $jurnal->jamSelesai?->jam_ke ? ' – '.$jurnal->jamSelesai->jam_ke : '' }}</span></td>
                @if($jenis === 'kelas')<td data-label="Guru">{{ $jurnal->guru?->nama_guru ?? '-' }}</td>@else<td data-label="Kelas">{{ $jurnal->kelas?->nama_kelas ?? '-' }}</td>@endif
                <td data-label="Mapel"><span class="rekap-subject">{{ $jurnal->jadwal?->mapel?->nama_mapel ?? '-' }}</span></td>
                <td data-label="Materi">{{ $jurnal->materi ?: '-' }}</td><td data-label="Hadir">{{ $jurnal->jml_hadir ?? 0 }}</td><td data-label="Tidak Hadir">{{ $jurnal->jml_tidak_hadir ?? 0 }}</td>
                <td data-label="Status"><span class="rekap-status">{{ $jurnal->status_validasi_guru ?? '-' }}</span></td>
                <td data-label="Detail"><a class="rekap-detail-link" href="{{ route('piket.jurnal.show', $jurnal) }}">Buka detail</a></td>
            </tr>
        @empty
            <tr><td colspan="9"><div class="rekap-empty"><span class="rekap-empty-icon"><span class="material-symbols-outlined">event_busy</span></span><div class="rekap-empty-title">Jurnal belum ditemukan</div><p class="rekap-empty-note">Belum ada jurnal yang sesuai dengan pilihan dan filter ini. Coba ubah kata pencarian atau periode.</p></div></td></tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="rekap-journal-mobile">
    <div class="rekap-journal-mobile-list">
        @forelse($jurnals as $jurnal)
            @php($otherLabel = $jenis === 'kelas' ? 'Guru' : 'Kelas')
            @php($otherValue = $jenis === 'kelas' ? ($jurnal->guru?->nama_guru ?? '-') : ($jurnal->kelas?->nama_kelas ?? '-'))
            <a class="rekap-journal-card" href="{{ route('piket.jurnal.show', $jurnal) }}">
                <div class="rekap-journal-top">
                    <div class="rekap-journal-time">{{ $jurnal->tanggal?->format('d M Y') ?? '-' }}<span>Jam {{ $jurnal->jamMulai?->jam_ke ?? '-' }}{{ $jurnal->jamSelesai?->jam_ke ? '–'.$jurnal->jamSelesai->jam_ke : '' }}</span></div>
                    <span class="rekap-status">{{ $jurnal->status_validasi_guru ?? '-' }}</span>
                </div>
                <div class="rekap-journal-context"><div class="rekap-journal-subject">{{ $jurnal->jadwal?->mapel?->nama_mapel ?? 'Mata pelajaran belum diisi' }}</div><div class="rekap-journal-who">{{ $otherLabel }}: {{ $otherValue }}</div></div>
                <div class="rekap-journal-material"><div class="rekap-journal-material-label">Materi</div><div class="rekap-journal-material-text">{{ $jurnal->materi ?: 'Materi belum dicatat.' }}</div></div>
                <div class="rekap-journal-bottom"><span class="rekap-journal-count">Hadir {{ $jurnal->jml_hadir ?? 0 }}</span><span class="rekap-journal-count rekap-journal-count--absent">Tidak hadir {{ $jurnal->jml_tidak_hadir ?? 0 }}</span><span class="rekap-journal-arrow"><span class="material-symbols-outlined">arrow_forward</span></span></div>
            </a>
        @empty
            <div class="rekap-journal-empty">Belum ada jurnal sesuai pilihan dan filter ini. Coba ubah kata pencarian atau periode.</div>
        @endforelse
    </div>
</div>
