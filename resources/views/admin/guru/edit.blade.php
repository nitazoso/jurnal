@extends('layouts.admin')

@section('title', 'Edit Guru - Jurnify')
@section('page-title', 'Edit Guru')

@push('styles')
<style>
    .edit-guru-page { width: 100%; }

    .edit-guru-header {
        margin-bottom: 24px;
        animation: fadeUp .45s ease both;
    }

    .edit-guru-header h2 {
        margin: 0 0 6px;
        font-size: 24px;
        font-weight: 700;
        color: #252525;
    }

    .edit-guru-header p {
        margin: 0;
        font-size: 14px;
        color: #777;
    }

    .edit-guru-card {
        width: 100%;
        max-width: 700px;
        padding: 28px;
        background: #fff;
        border: 1px solid #eee;
        border-radius: 14px;
        box-shadow: 0 3px 12px rgba(0,0,0,.04);
        box-sizing: border-box;
        animation: fadeUp .5s ease .08s both;
        transition: box-shadow .25s ease;
    }

    .edit-guru-card:hover {
        box-shadow: 0 7px 20px rgba(48,54,111,.06);
    }

    .edit-guru-errors {
        margin-bottom: 22px;
        padding: 13px 16px;
        border: 1px solid #f1d2d2;
        border-radius: 10px;
        background: #fff4f4;
        color: #c54848;
        font-size: 13px;
        animation: fadeUp .35s ease both;
    }

    .edit-guru-errors ul { margin: 0; padding-left: 20px; }
    .edit-guru-errors li { margin-bottom: 4px; }
    .edit-guru-errors li:last-child { margin-bottom: 0; }

    .edit-guru-field {
        margin-bottom: 20px;
        animation: fadeUp .4s ease both;
    }

    .edit-guru-field:nth-child(2) { animation-delay: .05s; }
    .edit-guru-field:nth-child(3) { animation-delay: .1s; }
    .edit-guru-field:nth-child(4) { animation-delay: .15s; }
    .edit-guru-field:last-of-type { margin-bottom: 26px; }

    .edit-guru-field label {
        display: block;
        margin-bottom: 8px;
        font-size: 13px;
        font-weight: 600;
        color: #444;
    }

    .edit-guru-field input {
        width: 100%;
        height: 44px;
        padding: 0 14px;
        border: 1px solid #ddd;
        border-radius: 9px;
        background: #fff;
        color: #333;
        font-family: inherit;
        font-size: 13px;
        outline: none;
        box-sizing: border-box;
        transition: border-color .2s ease, box-shadow .2s ease, transform .2s ease;
    }

    .edit-guru-field input:focus {
        border-color: #7886c7;
        box-shadow: 0 0 0 3px rgba(120,134,199,.12);
        transform: translateY(-1px);
    }

    .edit-guru-field input::placeholder { color: #aaa; }

    .edit-guru-buttons {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .edit-guru-cancel,
    .edit-guru-submit {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 42px;
        padding: 0 18px;
        border-radius: 9px;
        font-family: inherit;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        box-sizing: border-box;
        transition: .2s ease;
    }

    .edit-guru-cancel {
        border: 1px solid #ddd;
        background: #f5f5f5;
        color: #666;
    }

    .edit-guru-cancel:hover {
        background: #ebebeb;
        transform: translateY(-1px);
    }

    .edit-guru-submit {
        border: none;
        background: #30366f;
        color: #fff;
        box-shadow: 0 3px 8px rgba(48,54,111,.12);
    }

    .edit-guru-submit:hover {
        background: #252b5d;
        transform: translateY(-2px);
        box-shadow: 0 6px 13px rgba(48,54,111,.16);
    }

    .edit-guru-submit:active,
    .edit-guru-cancel:active {
        transform: scale(.97);
    }

    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 768px) {
        .edit-guru-card { max-width: none; padding: 22px; }

        .edit-guru-buttons {
            flex-direction: column-reverse;
            align-items: stretch;
        }

        .edit-guru-cancel,
        .edit-guru-submit { width: 100%; }
    }

    @media (prefers-reduced-motion: reduce) {
        .edit-guru-header,
        .edit-guru-card,
        .edit-guru-field,
        .edit-guru-errors { animation: none; }

        .edit-guru-field input,
        .edit-guru-cancel,
        .edit-guru-submit { transition: none; }
    }
</style>
@endpush

@section('content')

<div class="edit-guru-page">

    {{-- PAGE HEADER --}}
    <div class="edit-guru-header">
        <h2>Edit Guru</h2>
        <p>Perbarui data guru yang terdaftar dalam sistem.</p>
    </div>

    {{-- FORM CARD --}}
    <div class="edit-guru-card">

        {{-- ERROR --}}
        @if ($errors->any())
            <div class="edit-guru-errors">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.guru.update', $guru->id_guru) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- NIP --}}
            <div class="edit-guru-field">
                <label for="nip">NIP</label>
                <input id="nip" type="text" name="nip" value="{{ old('nip', $guru->nip) }}" placeholder="Masukkan NIP guru" required>
            </div>

            {{-- NAMA --}}
            <div class="edit-guru-field">
                <label for="nama_guru">Nama Guru</label>
                <input id="nama_guru" type="text" name="nama_guru" value="{{ old('nama_guru', $guru->nama_guru) }}" placeholder="Masukkan nama guru" required>
            </div>

            {{-- NO HP --}}
            <div class="edit-guru-field">
                <label for="no_hp">No. HP</label>
                <input id="no_hp" type="text" name="no_hp" value="{{ old('no_hp', $guru->no_hp) }}" placeholder="Contoh: 081234567890">
            </div>

            {{-- BUTTON --}}
            <div class="edit-guru-buttons">
                <a href="{{ route('admin.guru.index') }}" class="edit-guru-cancel">Batal</a>
                <button type="submit" class="edit-guru-submit">Simpan Perubahan</button>
            </div>
        </form>

    </div>
</div>

@endsection