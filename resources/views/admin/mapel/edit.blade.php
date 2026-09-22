@extends('layouts.admin')

@section('title', 'Edit Mata Pelajaran - Jurnify')
@section('page-title', 'Edit Mata Pelajaran')
@section('page-subtitle', 'Perbarui informasi data mata pelajaran')

@section('content')

{{-- Font Manrope --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<div class="mapel-edit-page">

    <div class="form-container">

        {{-- BACK BUTTON --}}
        <a href="{{ route('admin.mapel.index') }}" class="btn-back">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="M19 12H5M12 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span>Kembali ke Data Mapel</span>
        </a>

        {{-- HEADER TITLE CARD --}}
        <div class="header-card">
            <div class="header-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div class="header-info">
                <h1 class="header-title">Edit Mata Pelajaran</h1>
                <p class="header-subtitle">Perbarui nama atau informasi mata pelajaran yang terpilih</p>
            </div>
        </div>

        {{-- ERROR ALERT --}}
        @if($errors->any())
            <div class="alert alert-danger">
                <div class="alert-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="8" x2="12" y2="12"/>
                        <line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                </div>
                <div class="alert-content">
                    <h4 class="alert-title">Gagal Memperbarui Data</h4>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- FORM CARD --}}
        <div class="form-card">
            <div class="card-header-inner">
                <h2 class="card-title">Ubah Informasi Mata Pelajaran</h2>
                <p class="card-subtitle">Silakan sesuaikan nama mata pelajaran di bawah ini.</p>
            </div>

            <form action="{{ route('admin.mapel.update', $mapel->id_mapel) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card-body-inner">
                    <div class="form-group">
                        <label for="nama_mapel" class="form-label">
                            Nama Mata Pelajaran <span class="required">*</span>
                        </label>
                        
                        <div class="input-wrapper">
                            <input
                                type="text"
                                id="nama_mapel"
                                name="nama_mapel"
                                value="{{ old('nama_mapel', $mapel->nama_mapel) }}"
                                placeholder="Contoh: Pemrograman Web, Matematika, Biologi"
                                required
                                autofocus
                                class="form-input @error('nama_mapel') is-invalid @enderror"
                            >
                            <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 20h9"/>
                                <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/>
                            </svg>
                        </div>

                        <span class="form-hint">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" y1="16" x2="12" y2="12"/>
                                <line x1="12" y1="8" x2="12.01" y2="8"/>
                            </svg>
                            Perubahan nama akan langsung berdampak pada seluruh jadwal terkait.
                        </span>
                    </div>
                </div>

                {{-- FORM FOOTER / ACTION BUTTONS --}}
                <div class="card-footer-inner">
                    <a href="{{ route('admin.mapel.index') }}" class="btn-cancel">
                        Batal
                    </a>

                    <button type="submit" class="btn-submit">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" stroke-linecap="round" stroke-linejoin="round"/>
                            <polyline points="17 21 17 13 7 13 7 21" stroke-linecap="round" stroke-linejoin="round"/>
                            <polyline points="7 3 7 8 15 8" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        </div>

    </div>

</div>

<style>
    .mapel-edit-page,
    .mapel-edit-page * {
        font-family: 'Manrope', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        box-sizing: border-box;
    }

    .mapel-edit-page {
        width: 100%;
        padding: 24px 32px 48px;
        animation: mapelFadeUp 0.45s cubic-bezier(.16, 1, .3, 1) both;
    }

    .form-container {
        width: 100%;
        max-width: 1000px;
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    /* BACK BUTTON */
    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        font-weight: 700;
        color: #64748B;
        text-decoration: none;
        width: fit-content;
        transition: all 0.2s ease;
    }

    .btn-back svg {
        width: 18px;
        height: 18px;
        transition: transform 0.2s ease;
    }

    .btn-back:hover {
        color: #2D336B;
    }

    .btn-back:hover svg {
        transform: translateX(-3px);
    }

    /* HEADER CARD */
    .header-card {
        display: flex;
        align-items: center;
        gap: 20px;
        background: #FFFFFF;
        padding: 24px 28px;
        border-radius: 20px;
        border: 1px solid #E2E8F0;
        box-shadow: 0 4px 14px rgba(45, 51, 107, 0.03);
    }

    .header-icon {
        width: 56px;
        height: 56px;
        border-radius: 16px;
        background: #F0F3FF;
        color: #7886C7;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .header-icon svg {
        width: 28px;
        height: 28px;
    }

    .header-title {
        margin: 0;
        font-size: 22px;
        font-weight: 800;
        color: #2D336B;
        letter-spacing: -0.3px;
    }

    .header-subtitle {
        margin: 4px 0 0;
        font-size: 13.5px;
        color: #64748B;
        font-weight: 500;
    }

    /* ALERTS */
    .alert {
        display: flex;
        gap: 14px;
        padding: 18px 24px;
        border-radius: 16px;
        font-size: 14px;
    }

    .alert-danger {
        background: #FEF2F2;
        border: 1px solid #FECACA;
        color: #991B1B;
    }

    .alert-icon svg {
        width: 22px;
        height: 22px;
        flex-shrink: 0;
    }

    .alert-title {
        margin: 0 0 6px;
        font-weight: 700;
        font-size: 14px;
    }

    .alert-content ul {
        margin: 0;
        padding-left: 18px;
        font-weight: 500;
    }

    /* FORM CARD */
    .form-card {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 20px;
        box-shadow: 0 4px 14px rgba(45, 51, 107, 0.03);
        overflow: hidden;
    }

    .card-header-inner {
        padding: 24px 32px;
        border-bottom: 1px solid #F1F5F9;
        background: #FAFAFC;
    }

    .card-title {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
        color: #1E293B;
    }

    .card-subtitle {
        margin: 4px 0 0;
        font-size: 13.5px;
        color: #64748B;
        font-weight: 500;
    }

    .card-body-inner {
        padding: 32px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .form-label {
        font-size: 14px;
        font-weight: 700;
        color: #334155;
    }

    .required {
        color: #EF4444;
    }

    /* INPUT FIELD WITH ICON */
    .input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .form-input {
        width: 100%;
        padding: 14px 18px 14px 48px;
        background: #FFFFFF;
        border: 1.5px solid #CBD5E1;
        border-radius: 12px;
        font-size: 14.5px;
        font-weight: 600;
        color: #0F172A;
        outline: none;
        transition: all 0.2s ease;
    }

    .form-input::placeholder {
        color: #94A3B8;
        font-weight: 500;
    }

    .form-input:focus {
        border-color: #7886C7;
        box-shadow: 0 0 0 4px rgba(120, 134, 199, 0.15);
    }

    .form-input.is-invalid {
        border-color: #EF4444;
    }

    .input-icon {
        position: absolute;
        left: 16px;
        width: 20px;
        height: 20px;
        color: #94A3B8;
        pointer-events: none;
        transition: color 0.2s ease;
    }

    .form-input:focus + .input-icon {
        color: #7886C7;
    }

    .form-hint {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        color: #64748B;
        font-weight: 500;
        margin-top: 4px;
    }

    .form-hint svg {
        width: 15px;
        height: 15px;
        color: #94A3B8;
        flex-shrink: 0;
    }

    /* CARD FOOTER */
    .card-footer-inner {
        padding: 20px 32px;
        background: #F8FAFC;
        border-top: 1px solid #F1F5F9;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 12px;
    }

    .btn-cancel {
        padding: 11px 24px;
        background: #FFFFFF;
        border: 1px solid #CBD5E1;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 700;
        color: #475569;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .btn-cancel:hover {
        background: #F1F5F9;
        color: #0F172A;
    }

    .btn-submit {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 11px 26px;
        background: linear-gradient(135deg, #7886C7 0%, #2D336B 100%);
        color: #FFFFFF;
        border: none;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(45, 51, 107, 0.15);
        transition: all 0.25s ease;
    }

    .btn-submit:hover {
        transform: translateY(-1.5px);
        box-shadow: 0 6px 18px rgba(45, 51, 107, 0.25);
    }

    .btn-submit svg {
        width: 18px;
        height: 18px;
    }

    /* ANIMATION */
    @keyframes mapelFadeUp {
        from {
            opacity: 0;
            transform: translateY(12px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* RESPONSIVE */
    @media (max-width: 768px) {
        .mapel-edit-page {
            padding: 16px 16px 32px;
        }

        .card-header-inner,
        .card-body-inner,
        .card-footer-inner,
        .header-card {
            padding: 20px;
        }

        .card-footer-inner {
            flex-direction: column-reverse;
            gap: 10px;
        }

        .btn-cancel,
        .btn-submit {
            width: 100%;
            justify-content: center;
            text-align: center;
        }
    }
</style>

@endsection