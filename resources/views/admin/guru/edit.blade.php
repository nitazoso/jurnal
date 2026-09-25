@extends('layouts.admin')

@section('title', 'Edit Guru - Jurnify')
@section('page-title', 'Edit Guru')
@section('page-subtitle', 'Perbarui informasi data guru yang terdaftar dalam sistem')

@section('content')

{{-- Font Manrope --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<div class="guru-edit-page">

    <div class="form-container">

        {{-- BACK BUTTON --}}
        <a href="{{ route('admin.guru.index') }}" class="btn-back">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="M19 12H5M12 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span>Kembali ke Data Guru</span>
        </a>

        {{-- HEADER CARD --}}
        <div class="header-card">
            <div class="header-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" stroke-linecap="round" stroke-linejoin="round"/>
                    <circle cx="12" cy="7" r="4" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div class="header-info">
                <h1 class="header-title">Edit Data Guru</h1>
                <p class="header-subtitle">Perbarui nama lengkap atau kontak guru yang terpilih</p>
            </div>
        </div>

        {{-- ERROR ALERT --}}
        @if ($errors->any())
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
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- FORM CARD --}}
        <div class="form-card">
            <div class="card-header-inner">
                <h2 class="card-title">Informasi Guru</h2>
                <p class="card-subtitle">Pastikan NIP dan nama yang dimasukkan sudah sesuai.</p>
            </div>

            <form action="{{ route('admin.guru.update', $guru->id_guru) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card-body-inner">
                    <div class="form-grid">

                        {{-- NAMA GURU --}}
                        <div class="form-group">
                            <label for="nama_guru" class="form-label">
                                Nama Guru & Gelar <span class="required">*</span>
                            </label>
                            <input
                                type="text"
                                id="nama_guru"
                                name="nama_guru"
                                value="{{ old('nama_guru', $guru->nama_guru) }}"
                                placeholder="Contoh: Ahmad Fauzi, S.Pd., M.Pd."
                                required
                                class="form-input @error('nama_guru') is-invalid @enderror"
                            >
                            <span class="form-hint">Nama lengkap beserta gelar akademik.</span>
                        </div>

                        <div class="form-group">
                            <label for="no_hp" class="form-label">
                                Nomor HP
                            </label>
                            <input
                                type="text"
                                id="no_hp"
                                name="no_hp"
                                value="{{ old('no_hp', $guru->no_hp) }}"
                                placeholder="Contoh: 081234567890"
                                inputmode="tel"
                                class="form-input @error('no_hp') is-invalid @enderror"
                            >
                            <span class="form-hint">Opsional, dapat diubah kapan saja.</span>
                        </div>

                    </div>
                </div>

                {{-- FORM FOOTER / ACTIONS --}}
                <div class="card-footer-inner">
                    <a href="{{ route('admin.guru.index') }}" class="btn-cancel">
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
    .guru-edit-page,
    .guru-edit-page * {
        font-family: 'Manrope', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        box-sizing: border-box;
    }

    .guru-edit-page {
        width: 100%;
        padding: 0 0 32px;
        animation: guruFadeUp 0.45s cubic-bezier(.16, 1, .3, 1) both;
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

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 24px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .form-group.full-width {
        grid-column: span 2;
    }

    .form-label {
        font-size: 14px;
        font-weight: 700;
        color: #334155;
    }

    .required {
        color: #EF4444;
    }

    .form-input {
        width: 100%;
        padding: 12px 16px;
        background: #FFFFFF;
        border: 1px solid #CBD5E1;
        border-radius: 12px;
        font-size: 14px;
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

    .form-hint {
        font-size: 12.5px;
        color: #64748B;
        font-weight: 500;
    }

    /* CARD FOOTER */
    .card-footer-inner {
        padding: 20px 32px;
        background: #FAFBFD;
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
    @keyframes guruFadeUp {
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
        .form-grid {
            grid-template-columns: 1fr;
        }

        .form-group.full-width {
            grid-column: span 1;
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