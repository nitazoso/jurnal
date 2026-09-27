@php
    $selectedPeriod = request('periode');
    if (! $selectedPeriod && request('tahun') && request('bulan')) {
        $selectedPeriod = sprintf('%04d-%02d', request('tahun'), request('bulan'));
    }
@endphp
<form method="GET" action="{{ route('piket.jurnal.rekap') }}" class="rekap-filter-form">
    <input type="hidden" name="view" value="{{ $idKelas ? 'kelas' : 'guru' }}">
    @if($idKelas)<input type="hidden" name="id_kelas" value="{{ $idKelas }}">@endif
    @if($idGuru)<input type="hidden" name="id_guru" value="{{ $idGuru }}">@endif
    <label class="rekap-field rekap-search-wrap">Cari jurnal
        <input class="rekap-search" type="search" name="search" value="{{ request('search') }}" placeholder="Cari guru, kelas, atau materi…" aria-label="Cari jurnal">
    </label>
    <label class="rekap-field">Bulan &amp; Tahun
        <select class="rekap-select" name="periode" aria-label="Pilih bulan dan tahun">
            <option value="all" @selected(! $selectedPeriod)>Semua periode</option>
            @foreach($tahunList as $tahun)
                @foreach([1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'] as $angka => $nama)
                    @php($periodValue = sprintf('%04d-%02d', $tahun, $angka))
                    <option value="{{ $periodValue }}" @selected($selectedPeriod === $periodValue)>{{ $nama }} {{ $tahun }}</option>
                @endforeach
            @endforeach
        </select>
    </label>
    <div class="rekap-filter-actions">
        <a class="rekap-button rekap-button--light" aria-label="Reset filter" title="Reset filter" href="{{ route('piket.jurnal.rekap', $idKelas ? ['view' => 'kelas', 'id_kelas' => $idKelas] : ['view' => 'guru', 'id_guru' => $idGuru]) }}"><span class="material-symbols-outlined">restart_alt</span><span class="rekap-button-label">Reset</span></a>
        <button class="rekap-button" type="submit" aria-label="Terapkan filter" title="Terapkan filter"><span class="material-symbols-outlined">search</span><span class="rekap-button-label">Terapkan</span></button>
    </div>
</form>
