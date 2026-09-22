@extends('layouts.admin')

@section('title', 'Tambah Jadwal Piket - Jurnify')
@section('page-title', 'Tambah Jadwal Piket')
@section('page-subtitle', 'Tambahkan penugasan jadwal piket guru')

@section('content')

<div class="form-page">

    <div class="form-header">
        <div>
            <h2 class="form-title">Buat Jadwal Piket</h2>
            <p class="form-subtitle">
                Tentukan tanggal, guru, shift, jam, dan penugasan piket.
            </p>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-error">
            <div class="alert-icon">
                <span class="material-symbols-outlined">error</span>
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

    <div class="form-card">

        <div class="form-card-header">
            <div class="header-icon">
                <span class="material-symbols-outlined">event_note</span>
            </div>

            <div>
                <h3>Informasi Jadwal Piket</h3>
                <p>Isi data penugasan guru yang akan melakukan piket.</p>
            </div>
        </div>

        <div class="form-divider"></div>

        <form action="{{ route('admin.jadwal-piket.store') }}" method="POST">
            @csrf

            <div class="form-fields">
                @include('admin.jadwal-piket.form')
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.jadwal-piket.index') }}" class="btn-cancel">
                    Batal
                </a>

                <button type="submit" class="btn-submit">
                    <span class="material-symbols-outlined">save</span>
                    Simpan Jadwal
                </button>
            </div>
        </form>

    </div>

</div>

<style>
    .form-page {
        width: 100%;
        max-width: 960px;
        margin: 0 auto;
        padding-bottom: 40px;
    }

    .form-header {
        margin-bottom: 22px;
    }

    .form-title {
        margin: 0;
        font-size: 26px;
        font-weight: 700;
        color: #2D336B;
    }

    .form-subtitle {
        margin: 6px 0 0;
        font-size: 14px;
        color: #64748B;
    }

    .alert {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 15px 18px;
        margin-bottom: 20px;
        border-radius: 12px;
        font-size: 14px;
    }

    .alert-error {
        background: #FEF2F2;
        border: 1px solid #FECACA;
        color: #991B1B;
    }

    .alert-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .alert-icon .material-symbols-outlined {
        font-size: 20px;
    }

    .alert strong {
        display: block;
        margin-bottom: 5px;
    }

    .alert ul {
        margin: 0;
        padding-left: 18px;
    }

    .form-card {
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

    .header-icon .material-symbols-outlined {
        font-size: 23px;
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
    }

    .form-divider {
        height: 1px;
        background: #EEF0F5;
    }

    .form-fields {
        padding: 28px;
    }

    .form-fields > div {
        min-width: 0;
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

    .btn-submit .material-symbols-outlined {
        font-size: 18px;
    }

    @media (max-width: 700px) {
        .form-page {
            max-width: 100%;
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