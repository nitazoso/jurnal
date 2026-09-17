@extends('layouts.admin')

@section('title', 'Tambah Guru - Jurnify')
@section('page-title', 'Tambah Guru')

@push('styles')
<style>
    .guru-create-page {
        width: 100%;
        animation: pageFadeIn .45s ease both;
    }

    .guru-create-header {
        margin-bottom: 24px;
        animation: fadeDown .45s ease both;
    }

    .guru-create-header h2 {
        margin: 0 0 6px;
        font-size: 24px;
        font-weight: 700;
        color: #252525;
    }

    .guru-create-header p {
        margin: 0;
        font-size: 14px;
        color: #777;
    }

    .guru-form-card {
        width: 100%;
        max-width: 700px;
        padding: 28px;
        background: #ffffff;
        border: 1px solid #eeeeee;
        border-radius: 14px;
        box-shadow: 0 3px 12px rgba(0, 0, 0, 0.04);
        box-sizing: border-box;
        animation: cardUp .5s ease .08s both;
        transition: box-shadow .25s ease, transform .25s ease;
    }

    .guru-form-card:hover {
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
    }

    .guru-error-alert {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 24px;
        padding: 14px 16px;
        border: 1px solid #f1cccc;
        border-radius: 10px;
        background: #fff4f4;
        color: #c54848;
        font-size: 13px;
        animation: errorIn .4s ease both;
    }

    .guru-error-alert .material-symbols-outlined {
        font-size: 20px;
        margin-top: 1px;
        transition: transform .2s ease;
    }

    .guru-error-alert:hover .material-symbols-outlined {
        transform: scale(1.08);
    }

    .guru-error-alert ul {
        margin: 0;
        padding-left: 18px;
    }

    .guru-error-alert li {
        margin-bottom: 4px;
    }

    .guru-error-alert li:last-child {
        margin-bottom: 0;
    }

    .guru-form-group {
        margin-bottom: 20px;
        animation: fieldUp .4s ease both;
    }

    .guru-form-group:nth-of-type(2) {
        animation-delay: .05s;
    }

    .guru-form-group:nth-of-type(3) {
        animation-delay: .1s;
    }

    .guru-form-group:last-of-type {
        margin-bottom: 26px;
    }

    .guru-form-label {
        display: block;
        margin-bottom: 8px;
        font-size: 13px;
        font-weight: 600;
        color: #444;
        transition: color .2s ease;
    }

    .guru-form-group:focus-within .guru-form-label {
        color: #30366f;
    }

    .guru-required {
        color: #c54848;
    }

    .guru-form-input {
        width: 100%;
        height: 44px;
        padding: 0 14px;
        box-sizing: border-box;
        border: 1px solid #dddddd;
        border-radius: 9px;
        background: #ffffff;
        color: #333;
        font-family: inherit;
        font-size: 13px;
        outline: none;
        transition: border-color .2s ease, box-shadow .2s ease, transform .2s ease;
    }

    .guru-form-input::placeholder {
        color: #aaa;
    }

    .guru-form-input:hover {
        border-color: #c9c9c9;
    }

    .guru-form-input:focus {
        border-color: #7886c7;
        box-shadow: 0 0 0 3px rgba(120, 134, 199, 0.12);
        transform: translateY(-1px);
    }

    .guru-form-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        animation: fieldUp .4s ease .15s both;
    }

    .guru-cancel-button,
    .guru-save-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 40px;
        padding: 0 18px;
        border-radius: 8px;
        font-family: inherit;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: background .2s ease, border-color .2s ease, transform .2s ease, box-shadow .2s ease;
        box-sizing: border-box;
    }

    .guru-cancel-button {
        border: 1px solid #dddddd;
        background: #f5f5f5;
        color: #666;
    }

    .guru-cancel-button:hover {
        background: #eaeaea;
        transform: translateY(-1px);
    }

    .guru-cancel-button:active,
    .guru-save-button:active {
        transform: translateY(0);
    }

    .guru-save-button {
        border: 1px solid #30366f;
        background: #30366f;
        color: #ffffff;
    }

    .guru-save-button:hover {
        background: #252b5d;
        border-color: #252b5d;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(48, 54, 111, 0.18);
    }

    @keyframes pageFadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    @keyframes fadeDown {
        from {
            opacity: 0;
            transform: translateY(-8px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes cardUp {
        from {
            opacity: 0;
            transform: translateY(12px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fieldUp {
        from {
            opacity: 0;
            transform: translateY(7px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes errorIn {
        from {
            opacity: 0;
            transform: translateY(-5px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (max-width: 768px) {
        .guru-form-card {
            padding: 22px;
        }

        .guru-form-actions {
            flex-direction: column-reverse;
            align-items: stretch;
        }

        .guru-cancel-button,
        .guru-save-button {
            width: 100%;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .guru-create-page,
        .guru-create-header,
        .guru-form-card,
        .guru-error-alert,
        .guru-form-group,
        .guru-form-actions {
            animation: none;
        }

        .guru-form-input,
        .guru-cancel-button,
        .guru-save-button,
        .guru-form-card {
            transition: none;
        }
    }
</style>
@endpush

@section('content')
<div class="guru-create-page">

    {{-- PAGE HEADER --}}
    <div class="guru-create-header">
        <h2>Tambah Guru</h2>
        <p>Tambahkan data guru baru ke sistem Jurnify.</p>
    </div>

    {{-- FORM CARD --}}
    <div class="guru-form-card">

        {{-- ERROR --}}
        @if ($errors->any())
            <div class="guru-error-alert">
                <span class="material-symbols-outlined">error</span>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.guru.store') }}" method="POST">
            @csrf

            {{-- NIP --}}
            <div class="guru-form-group">
                <label class="guru-form-label">
                    NIP <span class="guru-required">*</span>
                </label>
                <input
                    type="text"
                    name="nip"
                    value="{{ old('nip') }}"
                    placeholder="Masukkan NIP guru"
                    maxlength="18"
                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-indigo-500"
                    required
                >
            </div>

            {{-- NAMA --}}
            <div class="guru-form-group">
                <label class="guru-form-label">
                    Nama Guru <span class="guru-required">*</span>
                </label>
                <input
                    type="text"
                    name="nama_guru"
                    value="{{ old('nama_guru') }}"
                    placeholder="Masukkan nama guru"
                    class="guru-form-input"
                    required
                >
            </div>

            {{-- BUTTON --}}
            <div class="guru-form-actions">
                <a
                    href="{{ route('admin.guru.index') }}"
                    class="guru-cancel-button"
                >
                    Batal
                </a>
                <button
                    type="submit"
                    class="guru-save-button"
                >
                    Simpan Guru
                </button>
            </div>
        </form>

    </div>
</div>
@endsection