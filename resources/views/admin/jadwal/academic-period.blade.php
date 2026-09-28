@extends('layouts.admin')

@section('title', 'Pengaturan Tahun Ajaran')
@section('page-title', 'Pengaturan Tahun Ajaran')

@section('content')
<div class="academic-period-page">
    <a href="{{ route('admin.jadwal.index') }}" class="academic-period-back">
        <span class="material-symbols-outlined" aria-hidden="true">arrow_back</span>
        Kembali ke Jadwal Pelajaran
    </a>

    @if(session('success'))
        <div class="academic-period-success" role="status">{{ session('success') }}</div>
    @endif

    <section class="academic-period-card">
        <div class="academic-period-heading">
            <span class="material-symbols-outlined" aria-hidden="true">calendar_month</span>
            <div>
                <h1>Tahun Ajaran Aktif</h1>
                <p>Periode ini menjadi acuan default untuk jadwal yang dibuat berikutnya.</p>
            </div>
        </div>

        <form action="{{ route('admin.jadwal.academic-period.update') }}" method="POST" class="academic-period-form">
            @csrf
            @method('PUT')

            <label>
                <span>Semester</span>
                <select name="semester" required>
                    <option value="">Pilih semester</option>
                    @foreach(['Ganjil', 'Genap'] as $semester)
                        <option value="{{ $semester }}" @selected(old('semester', $academicPeriod?->semester) === $semester)>{{ $semester }}</option>
                    @endforeach
                </select>
                @error('semester')
                    <small>{{ $message }}</small>
                @enderror
            </label>

            <label>
                <span>Tahun Ajaran</span>
                <input type="text" name="tahun_ajaran" value="{{ old('tahun_ajaran', $academicPeriod?->tahun_ajaran) }}" placeholder="Contoh: 2027/2028" maxlength="9" required>
                @error('tahun_ajaran')
                    <small>{{ $message }}</small>
                @enderror
            </label>

            <button type="submit">
                <span class="material-symbols-outlined" aria-hidden="true">save</span>
                Simpan Tahun Ajaran
            </button>
        </form>
    </section>
</div>

<style>
    .academic-period-page { display: grid; gap: 16px; max-width: 820px; }
    .academic-period-back { display: inline-flex; width: fit-content; align-items: center; gap: 7px; color: #4757b2; font-size: 12px; font-weight: 700; text-decoration: none; }
    .academic-period-back .material-symbols-outlined { font-size: 18px; }
    .academic-period-card { padding: 24px; border: 1px solid #e2e8f0; border-radius: 12px; background: #fff; box-shadow: 0 4px 14px rgba(30, 41, 59, .04); }
    .academic-period-heading { display: flex; align-items: center; gap: 12px; padding-bottom: 20px; border-bottom: 1px solid #eef2f7; }
    .academic-period-heading > .material-symbols-outlined { display: grid; width: 42px; height: 42px; place-items: center; border-radius: 10px; background: #eef2ff; color: #4757b2; font-size: 23px; }
    .academic-period-heading h1 { margin: 0; color: #202b61; font-size: 18px; font-weight: 800; }
    .academic-period-heading p { margin: 5px 0 0; color: #64748b; font-size: 12px; }
    .academic-period-form { display: grid; max-width: 520px; gap: 16px; padding-top: 20px; }
    .academic-period-form label { display: grid; gap: 7px; color: #475569; font-size: 12px; font-weight: 700; }
    .academic-period-form input, .academic-period-form select { width: 100%; min-height: 42px; padding: 9px 11px; border: 1px solid #cbd5e1; border-radius: 7px; background: #fff; color: #1e293b; font: inherit; font-size: 13px; }
    .academic-period-form input:focus, .academic-period-form select:focus { border-color: #4757b2; outline: 3px solid rgba(71, 87, 178, .14); }
    .academic-period-form small { color: #b42318; font-size: 11px; }
    .academic-period-form button { display: inline-flex; width: fit-content; min-height: 42px; align-items: center; gap: 8px; padding: 0 16px; border: 0; border-radius: 7px; background: #30366f; color: #fff; font: inherit; font-size: 12px; font-weight: 800; cursor: pointer; }
    .academic-period-form button .material-symbols-outlined { font-size: 18px; }
    .academic-period-success { padding: 12px 15px; border: 1px solid #a7f3d0; border-radius: 8px; background: #ecfdf5; color: #047857; font-size: 12px; font-weight: 700; }
    @media (max-width: 600px) { .academic-period-card { padding: 18px 16px; } .academic-period-heading { align-items: flex-start; } }
</style>
@endsection