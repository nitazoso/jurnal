@extends('layouts.admin')

@section('title', 'Tambah Jadwal Kesiswaan - Jurnify')
@section('page-title', 'Tambah Jadwal Kesiswaan')
@section('page-subtitle', 'Tambahkan jadwal petugas kesiswaan yang berwenang melakukan ACC dispen')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<div class="form-page">

    {{-- HEADER --}}
    <div class="form-header">
        <div>
            <h2 class="form-title">Buat Jadwal Petugas</h2>
            <p class="form-subtitle">
                Tentukan tanggal dan petugas kesiswaan yang bertugas melakukan ACC dispen.
            </p>
        </div>
    </div>

    {{-- ERROR ALERT --}}
    @if ($errors->any())
        <div class="alert alert-error">
            <div class="alert-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
            </div>

            <div>
                <strong>Data belum dapat disimpan</strong>
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

        <div class="form-card-header">
            <div class="header-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2"/>
                    <line x1="16" y1="2" x2="16" y2="6"/>
                    <line x1="8" y1="2" x2="8" y2="6"/>
                    <line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
            </div>

            <div>
                <h3>Informasi Jadwal</h3>
                <p>Isi data petugas kesiswaan yang akan bertugas.</p>
            </div>
        </div>

        <div class="form-divider"></div>

        <form action="{{ route('admin.jadwal-kesiswaan.store') }}" method="POST">
            @csrf

            <div class="form-fields">
                @include('admin.jadwal-kesiswaan.form')
            </div>

            <div class="form-actions">

                <a href="{{ route('admin.jadwal-kesiswaan.index') }}" class="btn-cancel">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 12H5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M12 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Batal
                </a>

                <button type="submit" class="btn-submit">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M5 12l4 4L19 6" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Simpan Jadwal
                </button>

            </div>
        </form>

    </div>

</div>

<style>
    .form-page,
    .form-page * {
        font-family: 'Manrope', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        box-sizing: border-box;
    }

    .form-page {
        width: 100%;
        max-width: 960px;
        margin: 0 auto;
        padding-bottom: 40px;
        animation: formFadeUp 0.45s cubic-bezier(.16, 1, .3, 1) both;
    }

    /* HEADER */
    .form-header {
        margin-bottom: 22px;
    }

    .form-title {
        margin: 0;
        font-size: 26px;
        font-weight: 700;
        color: #2D336B;
        line-height: 1.3;
        letter-spacing: -0.4px;
    }

    .form-subtitle {
        margin: 6px 0 0;
        font-size: 14px;
        line-height: 1.6;
        color: #64748B;
        font-weight: 500;
    }

    /* ERROR ALERT */
    .alert {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 15px 18px;
        margin-bottom: 20px;
        border-radius: 12px;
        font-size: 14px;
        line-height: 1.5;
    }

    .alert-error {
        background: #FEF2F2;
        border: 1px solid #FECACA;
        color: #991B1B;
    }

    .alert-icon {
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        margin-top: 1px;
    }

    .alert-icon svg {
        width: 20px;
        height: 20px;
    }

    .alert strong {
        display: block;
        margin-bottom: 5px;
        font-weight: 800;
    }

    .alert ul {
        margin: 0;
        padding-left: 18px;
    }

    .alert li {
        margin: 2px 0;
    }

    /* FORM CARD */
    .form-card {
        width: 100%;
        background: #FFFFFF;
        border: 1px solid #E5E7EB;
        border-radius: 20px;
        box-shadow: 0 4px 18px rgba(45, 51, 107, 0.06);
        overflow: hidden;
    }

    .form-card-header {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 24px 28px;
    }

    .header-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: #DCE4FF;
        color: #2D336B;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .header-icon svg {
        width: 23px;
        height: 23px;
    }

    .form-card-header h3 {
        margin: 0;
        font-size: 17px;
        font-weight: 700;
        color: #1E293B;
    }

    .form-card-header p {
        margin: 4px 0 0;
        font-size: 13px;
        color: #64748B;
        font-weight: 500;
    }

    .form-divider {
        height: 1px;
        background: #EEF0F5;
    }

    /* FORM FIELDS */
    .form-fields {
        padding: 28px;
    }

    .form-fields label {
        display: block;
        margin-bottom: 8px;
        font-size: 13px;
        font-weight: 600;
        color: #334155;
    }

    .form-fields input,
    .form-fields select,
    .form-fields textarea {
        width: 100%;
        box-sizing: border-box;
        padding: 11px 13px;
        border: 1px solid #D7DBE5;
        border-radius: 10px;
        background: #FFFFFF;
        color: #1E293B;
        font-family: 'Manrope', sans-serif;
        font-size: 13px;
        outline: none;
        transition: 0.2s ease;
    }

    .form-fields input,
    .form-fields select {
        min-height: 44px;
    }

    .form-fields textarea {
        min-height: 110px;
        resize: vertical;
    }

    .form-fields input:focus,
    .form-fields select:focus,
    .form-fields textarea:focus {
        border-color: #7886C7;
        box-shadow: 0 0 0 3px rgba(120, 134, 199, 0.12);
    }

    .form-fields input::placeholder,
    .form-fields textarea::placeholder {
        color: #94A3B8;
    }

    .form-fields .text-danger,
    .form-fields .error,
    .form-fields small.text-danger {
        display: block;
        margin-top: 6px;
        font-size: 12px;
        color: #DC2626;
    }

    /* ACTIONS */
    .form-actions {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 10px;
        padding: 20px 28px;
        border-top: 1px solid #EEF0F5;
        background: #FAFAFC;
    }

    .btn-cancel,
    .btn-submit {
        min-height: 42px;
        padding: 0 18px;
        border-radius: 10px;
        font-family: 'Manrope', sans-serif;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        cursor: pointer;
        transition: 0.2s ease;
        box-sizing: border-box;
    }

    .btn-cancel {
        color: #475569;
        background: #FFFFFF;
        border: 1px solid #D7DBE5;
    }

    .btn-cancel:hover {
        background: #F8FAFC;
        border-color: #CBD5E1;
    }

    .btn-submit {
        color: #FFFFFF;
        background: linear-gradient(135deg, #2D336B, #7886C7);
        border: none;
        box-shadow: 0 4px 10px rgba(45, 51, 107, 0.18);
    }

    .btn-submit:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 14px rgba(45, 51, 107, 0.24);
    }

    .btn-cancel svg,
    .btn-submit svg {
        width: 17px;
        height: 17px;
        flex-shrink: 0;
    }

    /* ANIMATION */
    @keyframes formFadeUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* RESPONSIVE */
    @media (max-width: 700px) {
        .form-page {
            max-width: 100%;
        }

        .form-title {
            font-size: 23px;
        }

        .form-card-header {
            padding: 20px;
        }

        .form-fields {
            padding: 20px;
        }

        .form-actions {
            padding: 18px 20px;
            flex-direction: column-reverse;
        }

        .btn-cancel,
        .btn-submit {
            width: 100%;
        }
    }
</style>

@endsection