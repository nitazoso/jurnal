@extends('layouts.piket')

@section('title', 'Isi Jurnal Guru - Jurnify')
@section('page-title', 'Isi Jurnal Guru')

@push('styles')
<style>
    .journal-entry-page { display: grid; gap: 18px; }
    .journal-entry-heading h1 { margin: 0; color: #30366f; font-size: 22px; }
    .journal-entry-heading p { margin: 5px 0 0; color: #64748b; font-size: 13px; }
    .journal-entry-section { padding: 20px; background: #fff; border: 1px solid #e1e7f0; border-radius: 10px; }
    .journal-entry-section h2 { margin: 0 0 14px; color: #30366f; font-size: 15px; }
    .class-picker { display: flex; align-items: end; flex-wrap: wrap; gap: 10px; }
    .class-picker label { display: grid; flex: 1 1 240px; gap: 6px; color: #475569; font-size: 12px; font-weight: 700; }
    .class-picker select { min-height: 42px; padding: 8px 11px; border: 1px solid #d7deeb; border-radius: 7px; background: #fff; color: #1f2937; font: inherit; }
    .schedule-action { min-height: 40px; padding: 9px 14px; border: 0; border-radius: 7px; background: #30366f; color: #fff; font: inherit; font-size: 12px; font-weight: 700; text-decoration: none; cursor: pointer; }
    .schedule-days { display: grid; gap: 20px; }
    .schedule-day-group { display: grid; gap: 9px; }
    .schedule-day-heading { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 0 0 8px; border-bottom: 1px solid #dbe3ef; }
    .schedule-day-heading h3 { margin: 0; padding-left: 10px; border-left: 3px solid #4169ff; color: #30366f; font-size: 14px; font-weight: 800; }
    .schedule-day-heading span { color: #64748b; font-size: 11px; font-weight: 700; }
    .schedule-list { display: grid; gap: 8px; }
    .schedule-row { display: grid; grid-template-columns: minmax(0, 1fr) auto; align-items: center; gap: 16px; padding: 14px; border: 1px solid #e5eaf2; border-radius: 8px; }
    .schedule-row h3 { margin: 0; color: #1f2937; font-size: 14px; }
    .schedule-row p { margin: 5px 0 0; color: #64748b; font-size: 12px; line-height: 1.5; }
    .schedule-state { color: #64748b; font-size: 12px; font-weight: 700; text-align: right; }
    .schedule-state.done { color: #187548; }
    .schedule-action:disabled { background: #e8edf3; color: #64748b; cursor: not-allowed; }
    .journal-empty-state { padding: 20px 0; color: #64748b; font-size: 13px; text-align: center; }
    .schedule-today-badge { padding: 4px 8px; border-radius: 20px; background: #dcfce7; color: #15803d !important; font-size: 10px !important; font-weight: 800; }
    @media (max-width: 620px) {
        .schedule-row { grid-template-columns: 1fr; }
        .schedule-row-action { display: flex; align-items: center; justify-content: space-between; gap: 10px; }
        .schedule-state { text-align: left; }
    }
</style>
@endpush

@section('content')
<div class="journal-entry-page">
    @if($allDisabled)
        <section class="journal-entry-section" role="status" style="padding: 32px 20px; text-align: center;">
            <svg width="38" height="38" fill="none" stroke="#b45309" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                <circle cx="12" cy="12" r="9" />
                <path d="m5.6 5.6 12.8 12.8" stroke-linecap="round" />
            </svg>
            <h2 style="margin: 12px 0 6px;">Pengisian jurnal sementara dinonaktifkan</h2>
            <p class="journal-empty-state" style="padding: 0;">Mode event sedang aktif. Pemilihan jadwal dan pengisian jurnal akan tersedia kembali setelah mode event dinonaktifkan.</p>
        </section>
    @else
    <header class="journal-entry-heading">
        <h1>Isi Jurnal Guru</h1>
        <p>Pilih kelas untuk melihat seluruh jadwal mengajar yang dikelola admin.</p>
    </header>

    <section class="journal-entry-section">
        <h2>Pilih Kelas</h2>
        <form action="{{ route('piket.jurnal.create') }}" method="GET" class="class-picker">
            <label for="id_kelas">
                Kelas
                <select id="id_kelas" name="id_kelas" required>
                    <option value="">Pilih kelas</option>
                    @foreach($kelases as $kelas)
                        <option value="{{ $kelas->id_kelas }}" {{ (string) $selectedKelas?->id_kelas === (string) $kelas->id_kelas ? 'selected' : '' }}>
                            {{ $kelas->nama_kelas }}
                        </option>
                    @endforeach
                </select>
            </label>
        </form>
    </section>
    <script>
        document.getElementById('id_kelas')?.addEventListener('change', event => {
            event.currentTarget.form?.requestSubmit();
        });
    </script>

    @if($selectedKelas)
        <section class="journal-entry-section">
            <h2>Pilih Guru dan Jadwal · {{ $selectedKelas->nama_kelas }}</h2>
            @if($jadwals->isEmpty())
                <p class="journal-empty-state">Belum ada jadwal mengajar untuk kelas ini.</p>
            @else
                <div class="schedule-days">
                    @foreach($jadwals->groupBy('hari') as $hari => $jadwalHarian)
                        <section class="schedule-day-group">
                            <header class="schedule-day-heading">
                                <h3>{{ $hari }}</h3>
                                @if($hari === $hariIni)<span class="schedule-today-badge">Hari ini</span>@else<span>{{ $jadwalHarian->count() }} jadwal</span>@endif
                            </header>
                            <div class="schedule-list">
                                @foreach($jadwalHarian as $jadwal)
                                    @php($sudahDiisi = $jadwal->jurnal_hari_ini_count > 0)
                                    <article class="schedule-row">
                                        <div>
                                            <h3>{{ $jadwal->guru?->nama_guru ?? 'Guru tidak ditemukan' }} · {{ $jadwal->mapel?->nama_mapel ?? 'Mapel tidak ditemukan' }}</h3>
                                            <p>
                                                Jam {{ $jadwal->jamMulai?->jam_ke ?? '-' }}–{{ $jadwal->jamSelesai?->jam_ke ?? '-' }}
                                                · {{ $jadwal->kelas?->nama_kelas ?? '-' }}
                                                · {{ $jadwal->semester }} {{ $jadwal->tahun_ajaran }}
                                            </p>
                                        </div>
                                        <div class="schedule-row-action">
                                            <span class="schedule-state {{ $sudahDiisi ? 'done' : '' }}">
                                                @if($hari !== $hariIni) Jadwal bukan hari ini
                                                @else{{ $sudahDiisi ? 'Jurnal hari ini sudah diisi' : 'Belum diisi hari ini' }}@endif
                                            </span>
                                            @if($sudahDiisi && $hari === $hariIni)
                                                <button class="schedule-action" type="button" disabled>Sudah Diisi</button>
                                            @elseif($hari !== $hariIni)
                                                <button class="schedule-action" type="button" disabled aria-disabled="true" title="Jurnal hanya dapat diisi pada hari ini">Bukan Hari Ini</button>
                                            @else
                                                <a class="schedule-action" href="{{ route('piket.jurnal.form', $jadwal) }}">Isi Jurnal</a>
                                            @endif
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                        </section>
                    @endforeach
                </div>
            @endif
        </section>
    @endif
    @endif
</div>
@endsection