```blade
@extends('layouts.admin')

@section('title', 'Manajemen Murid')

@section('content')

<style>
    /* =========================================================
       1. GLOBAL TRANSITIONS & RIPPLE EFFECTS
       ========================================================= */

    .btn-animated {
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .btn-animated:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(27, 35, 74, 0.15);
    }

    .btn-animated:active {
        transform: translateY(0) scale(0.98);
    }

    .btn-danger-animated {
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .btn-danger-animated:hover {
        background-color: #ffe4e6 !important;
        border-color: #f43f5e !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(225, 29, 72, 0.15);
    }

    .btn-danger-animated:active {
        transform: translateY(0) scale(0.98);
    }

    .btn-icon-hover {
        transition: all 0.2s ease;
        border-radius: 8px;
    }

    .btn-icon-hover:hover {
        background-color: #f1f5f9;
        transform: scale(1.15);
    }

    /* =========================================================
       2. CUSTOM INPUT FOCUS
       ========================================================= */

    .custom-input {
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid #cbd5e1;
    }

    .custom-input:focus {
        border-color: #4f46e5 !important;
        background-color: #ffffff !important;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
    }

    /* =========================================================
       3. INPUT ERROR
       ========================================================= */

    .input-error {
        border-color: #f43f5e !important;
        background-color: #fff1f2 !important;
    }

    .input-error:focus {
        border-color: #e11d48 !important;
        box-shadow: 0 0 0 3px rgba(225, 29, 72, 0.12) !important;
    }

    .duplicate-note {
        display: none;
        align-items: flex-start;
        gap: 6px;
        margin-top: 7px;
        padding: 8px 10px;
        border-radius: 8px;
        background-color: #fff1f2;
        border: 1px solid #fecdd3;
        color: #be123c;
        font-size: 12px;
        line-height: 1.4;
    }

    .duplicate-note.show {
        display: flex;
    }

    /* =========================================================
       4. INTERACTIVE TABLE
       ========================================================= */

    .table-row-interactive {
        transition: background-color 0.2s ease, transform 0.2s ease;
    }

    .table-row-interactive:hover {
        background-color: #f8fafc !important;
    }

    /* =========================================================
       5. DROPDOWN ITEM
       ========================================================= */

    .wali-item {
        transition: all 0.15s ease;
    }

    .wali-item:hover {
        background-color: #f0f9ff !important;
        color: #0284c7 !important;
        padding-left: 16px !important;
    }

    /* =========================================================
       6. BADGE WALI KELAS
       ========================================================= */

    .badge-wali-hover {
        border: 1px solid transparent;
    }

    .badge-wali-hover:hover {
        background-color: #e2e8f0 !important;
        border-color: #cbd5e1;
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    }

    .badge-wali-hover:active {
        transform: translateY(0);
    }

    .badge-wali-hover:hover .icon-edit-wali {
        transform: scale(1.15);
        transition: transform 0.15s ease;
    }

    /* =========================================================
       7. MODAL ANIMATIONS
       ========================================================= */

    @keyframes fadeInOverlay {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @keyframes modalScaleUp {
        from {
            opacity: 0;
            transform: scale(0.95) translateY(10px);
        }

        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }

    .modal-backdrop {
        animation: fadeInOverlay 0.25s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    }

    .modal-content-animated {
        animation: modalScaleUp 0.3s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
    }

    /* =========================================================
       8. TABLE ENTRANCE
       ========================================================= */

    @keyframes rowFadeIn {
        from {
            opacity: 0;
            transform: translateY(4px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .table-wrapper tbody tr {
        animation: rowFadeIn 0.3s ease-out forwards;
    }
</style>


<!-- =========================================================
     TOP ACTION / HEADER DETAIL KELAS
     ========================================================= -->

<div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px;">

    <div style="display: flex; align-items: flex-start; gap: 16px;">

        <!-- Tombol Kembali -->
        <a href="{{ route('admin.kelas.index') }}"
           class="btn-icon-hover"
           style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; background-color: #f1f5f9; border-radius: 50%; color: #334155; text-decoration: none; margin-top: 4px;"
           title="Kembali">

            <span class="material-symbols-outlined" style="font-size: 20px;">
                arrow_back
            </span>
        </a>

        <div>

            <span style="font-size: 11px; font-weight: 700; color: #64748b; letter-spacing: 0.5px; text-transform: uppercase;">
                MANAJEMEN MURID
            </span>

            <h2 style="margin: 2px 0 10px 0; font-size: 26px; font-weight: 800; color: #1e293b;">
                {{ $kelas->nama_kelas ?? 'Detail Kelas' }}
            </h2>

            <!-- Badge Wali Kelas -->
            <div onclick="openModalEditWali('{{ $kelas->id_kelas }}', '{{ $kelas->wali_kelas }}')"
                 class="badge-wali-hover"
                 style="display: inline-flex; align-items: center; gap: 8px; background-color: #f1f5f9; padding: 6px 14px; border-radius: 10px; font-size: 13px; color: #475569; font-weight: 500; cursor: pointer; transition: all 0.2s ease; user-select: none;">

                <span class="material-symbols-outlined" style="font-size: 18px; color: #64748b;">
                    assignment_ind
                </span>

                <span>
                    Wali Kelas:
                    <strong>
                        {{ $kelas->waliKelas->nama_guru ?? $kelas->waliKelas->nama ?? '-' }}
                    </strong>
                </span>

                <span class="icon-edit-wali"
                      style="display: inline-flex; align-items: center; color: #4f46e5; margin-left: 2px;">

                    <span class="material-symbols-outlined" style="font-size: 16px;">
                        edit
                    </span>
                </span>
            </div>

        </div>
    </div>


    <!-- Group Tombol Aksi Kanan -->
    <div style="display: flex; gap: 12px; align-items: center;">

        <!-- Form Hapus Kelas -->
        <form id="formDeleteKelas"
              action="{{ route('admin.kelas.destroy', $kelas->id_kelas ?? $kelas->id ?? 1) }}"
              method="POST">

            @csrf
            @method('DELETE')

            <button type="button"
                    onclick="confirmDeleteKelas('{{ $kelas->nama_kelas ?? 'Kelas Ini' }}')"
                    class="btn-danger-animated"
                    style="display: inline-flex; align-items: center; gap: 8px; border: 1px solid #fecdd3; background-color: #fff1f2; color: #e11d48; padding: 10px 18px; border-radius: 10px; font-weight: 600; font-size: 13px; cursor: pointer;">

                <span class="material-symbols-outlined" style="font-size: 18px;">
                    delete
                </span>

                Hapus Kelas
            </button>
        </form>


        <!-- Tombol Buka Modal Tambah Murid -->
        <button type="button"
                onclick="openModalTambah()"
                class="btn-animated"
                style="display: inline-flex; align-items: center; gap: 8px; background-color: #1B234A; color: #ffffff; padding: 10px 18px; border-radius: 10px; font-weight: 600; font-size: 13px; border: none; cursor: pointer;">

            <span class="material-symbols-outlined" style="font-size: 18px;">
                person_add
            </span>

            Tambah Murid
        </button>

    </div>
</div>


<!-- =========================================================
     CARD UTAMA DATA MURID
     ========================================================= -->

<div class="activity-card"
     style="background: #ffffff; border-radius: 16px; padding: 24px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.02); transition: box-shadow 0.3s ease;">

    <!-- Filter Search & Total Murid -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">

        <form action="{{ route('admin.kelas.siswa') }}"
              method="GET"
              style="position: relative; display: flex; align-items: center; width: 320px;">

            <input type="hidden"
                   name="kelas_id"
                   value="{{ $kelas_id ?? $kelas->id_kelas ?? '' }}">

            <span class="material-symbols-outlined"
                  style="position: absolute; left: 12px; color: #94a3b8; font-size: 20px; pointer-events: none;">
                search
            </span>

            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Cari nama atau NIS... (Tekan Enter)"
                   class="custom-input"
                   style="width: 100%; padding: 10px 14px 10px 40px; background: #f1f5f9; border-radius: 10px; font-size: 13px; outline: none; color: #334155;">
        </form>


        <div style="font-size: 13px; color: #64748b; font-weight: 500;">

            Total:

            <span style="background-color: #e0e7ff; color: #3730a3; padding: 4px 12px; border-radius: 12px; font-weight: 700; margin-left: 6px; display: inline-block; transition: transform 0.2s ease;"
                  onmouseover="this.style.transform='scale(1.08)'"
                  onmouseout="this.style.transform='scale(1)'">

                {{ $siswas->total() }} Siswa

            </span>
        </div>

    </div>


    <!-- Tabel Data Murid -->
    <div class="table-wrapper">

        <table style="width: 100%; border-collapse: separate; border-spacing: 0;">

            <thead>

                <tr style="background-color: #f8fafc; color: #64748b; font-size: 11px; text-transform: uppercase; font-weight: 700;">

                    <th style="padding: 14px 16px; text-align: center; width: 100px; border-top-left-radius: 10px; border-bottom-left-radius: 10px;">
                        NO. PRESENSI
                    </th>

                    <th style="padding: 14px 16px; text-align: left;">
                        Nama Murid
                    </th>

                    <th style="padding: 14px 16px; text-align: center;">
                        NIS
                    </th>

                    <th style="padding: 14px 16px; text-align: center;">
                        Jenis Kelamin
                    </th>

                    <th style="padding: 14px 16px; text-align: center; width: 100px; border-top-right-radius: 10px; border-bottom-right-radius: 10px;">
                        Aksi
                    </th>

                </tr>
            </thead>

            <tbody style="font-size: 13px;">

                @forelse($siswas ?? [] as $siswa)

                    <tr class="table-row-interactive"
                        style="border-bottom: 1px solid #f1f5f9;">

                        <td style="padding: 16px; text-align: center; color: #1B234A; font-weight: 700;">
                            {{ str_pad($siswa->no_presensi, 2, '0', STR_PAD_LEFT) }}
                        </td>

                        <td style="padding: 16px;">

                            <div style="display: flex; align-items: center; gap: 12px;">

                                <div style="width: 32px; height: 32px; background-color: #2b386b; color: #ffffff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 12px; transition: transform 0.2s ease;"
                                     onmouseover="this.style.transform='scale(1.15)'"
                                     onmouseout="this.style.transform='scale(1)'">

                                    {{ strtoupper(substr($siswa->nama_siswa ?? 'S', 0, 1)) }}

                                </div>

                                <span style="font-weight: 700; color: #1e293b;">
                                    {{ $siswa->nama_siswa }}
                                </span>

                            </div>
                        </td>

                        <td style="padding: 16px; text-align: center; color: #64748b; font-weight: 500;">
                            {{ $siswa->nis }}
                        </td>

                        <td style="padding: 16px; text-align: center;">

                            @if(($siswa->jenis_kelamin ?? 'L') == 'L')

                                <span style="display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; background-color: #e0e7ff; color: #3730a3; border-radius: 50%; transition: transform 0.2s ease;"
                                      onmouseover="this.style.transform='scale(1.2)'"
                                      onmouseout="this.style.transform='scale(1)'"
                                      title="Laki-laki">

                                    <span class="material-symbols-outlined" style="font-size: 16px;">
                                        male
                                    </span>

                                </span>

                            @else

                                <span style="display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; background-color: #fce7f3; color: #9d174d; border-radius: 50%; transition: transform 0.2s ease;"
                                      onmouseover="this.style.transform='scale(1.2)'"
                                      onmouseout="this.style.transform='scale(1)'"
                                      title="Perempuan">

                                    <span class="material-symbols-outlined" style="font-size: 16px;">
                                        female
                                    </span>

                                </span>

                            @endif

                        </td>

                        <td style="padding: 16px; text-align: center;">

                            <div style="display: flex; justify-content: center; align-items: center; gap: 8px;">

                                <!-- Tombol Edit -->
                                <button type="button"
                                        onclick='openModalEditSiswa(
                                            @json($siswa->id_siswa),
                                            @json($siswa->nama_siswa),
                                            @json($siswa->nis),
                                            @json($siswa->jenis_kelamin)
                                        )'
                                        class="btn-icon-hover"
                                        style="background: none; border: none; color: #3b82f6; cursor: pointer; padding: 6px; display: flex; align-items: center;"
                                        title="Edit Siswa">

                                    <span class="material-symbols-outlined" style="font-size: 18px;">
                                        edit
                                    </span>

                                </button>


                                <!-- Tombol Hapus -->
                                <form id="formDeleteSiswa-{{ $siswa->id_siswa }}"
                                      action="{{ route('admin.siswa.destroy', $siswa->id_siswa) }}"
                                      method="POST"
                                      style="display: inline;">

                                    @csrf
                                    @method('DELETE')

                                    <button type="button"
                                            onclick='confirmDeleteSiswa(
                                                @json($siswa->id_siswa),
                                                @json($siswa->nama_siswa)
                                            )'
                                            class="btn-icon-hover"
                                            style="background: none; border: none; color: #ef4444; cursor: pointer; padding: 6px; display: flex; align-items: center;"
                                            title="Hapus Siswa">

                                        <span class="material-symbols-outlined" style="font-size: 18px;">
                                            delete
                                        </span>

                                    </button>

                                </form>

                            </div>
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="5"
                            style="padding: 32px; text-align: center; color: #94a3b8;">
                            Belum ada data siswa di kelas ini.
                        </td>
                    </tr>

                @endforelse

            </tbody>
        </table>

    </div>


    <!-- Pagination -->
    @if($siswas->hasPages())

        <div style="margin-top: 20px; display: flex; justify-content: flex-end;">
            {{ $siswas->appends(request()->query())->links() }}
        </div>

    @endif

</div>


<!-- =========================================================
     MODAL EDIT WALI KELAS
     ========================================================= -->

<div id="modalEditWali"
     class="modal-backdrop"
     style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.5); backdrop-filter: blur(4px); justify-content: center; align-items: center; z-index: 9999;">

    <div class="modal-content-animated"
         style="background: #ffffff; width: 100%; max-width: 440px; border-radius: 16px; padding: 24px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);">

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">

            <h3 style="margin: 0; font-size: 18px; font-weight: 700; color: #1e293b;">
                Ubah Wali Kelas
            </h3>

            <button type="button"
                    onclick="closeModalEditWali()"
                    class="btn-icon-hover"
                    style="background: none; border: none; font-size: 20px; cursor: pointer; color: #64748b; padding: 2px 6px;">
                &times;
            </button>

        </div>


        <form id="formEditWali"
              action=""
              method="POST"
              onsubmit="return confirmVerifikasiWali(event)">

            @csrf
            @method('PUT')

            <input type="hidden"
                   name="nama_kelas"
                   value="{{ $kelas->nama_kelas }}">

            <div style="margin-bottom: 20px; position: relative;">

                <label style="display: block; font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 6px;">
                    Cari / Pilih Wali Kelas
                </label>

                <input type="hidden"
                       name="wali_kelas"
                       id="hiddenWaliId"
                       required>

                <input type="text"
                       id="inputSearchWali"
                       placeholder="Ketik nama guru..."
                       onfocus="showWaliList()"
                       oninput="filterWaliList()"
                       autocomplete="off"
                       class="custom-input"
                       style="width: 100%; padding: 10px 12px; background: #f8fafc; border-radius: 8px; font-size: 13px; outline: none; box-sizing: border-box;"
                       required>

                <div id="dropdownWaliList"
                     style="display: none; position: absolute; left: 0; right: 0; top: 100%; margin-top: 4px; background: #ffffff; border: 1px solid #cbd5e1; border-radius: 8px; max-height: 180px; overflow-y: auto; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); z-index: 100;">

                    @foreach($gurus as $g)

                        @php
                            $namaG = $g->nama_guru ?? $g->nama;
                        @endphp

                        <div class="wali-item"
                             data-id="{{ $g->id_guru }}"
                             data-nama="{{ $namaG }}"
                             onclick='selectWali(@json($g->id_guru), @json($namaG))'
                             style="padding: 10px 12px; font-size: 13px; color: #334155; cursor: pointer; border-bottom: 1px solid #f1f5f9;">

                            {{ $namaG }}

                        </div>

                    @endforeach

                    <div id="noWaliFound"
                         style="display: none; padding: 10px 12px; font-size: 13px; color: #94a3b8; text-align: center;">

                        Guru tidak ditemukan

                    </div>

                </div>

            </div>


            <div style="display: flex; justify-content: flex-end; gap: 10px;">

                <button type="button"
                        onclick="closeModalEditWali()"
                        style="padding: 8px 16px; border-radius: 8px; border: 1px solid #cbd5e1; background: #fff; color: #475569; font-weight: 600; font-size: 13px; cursor: pointer;">
                    Batal
                </button>

                <button type="submit"
                        class="btn-animated"
                        style="padding: 8px 16px; border-radius: 8px; background: #1B234A; color: #fff; border: none; font-weight: 600; font-size: 13px; cursor: pointer;">
                    Simpan Perubahan
                </button>

            </div>

        </form>

    </div>
</div>


<!-- =========================================================
     MODAL TAMBAH MURID
     ========================================================= -->

<div id="modalTambah"
     class="modal-backdrop"
     style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.5); backdrop-filter: blur(4px); justify-content: center; align-items: center; z-index: 9999;">

    <div class="modal-content-animated"
         style="background: #ffffff; width: 100%; max-width: 480px; border-radius: 16px; padding: 24px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);">

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">

            <h3 style="margin: 0; font-size: 18px; font-weight: 700; color: #1e293b;">
                Tambah Murid Baru
            </h3>

            <button type="button"
                    onclick="closeModalTambah()"
                    class="btn-icon-hover"
                    style="background: none; border: none; font-size: 20px; cursor: pointer; color: #64748b; padding: 2px 6px;">
                &times;
            </button>

        </div>


        <form id="formTambahMurid"
              action="{{ route('admin.siswa.store') }}"
              method="POST"
              onsubmit="return confirmVerifikasiTambah(event)">

            @csrf

            <!-- ERROR KHUSUS TAMBAH -->
            @if ($errors->any() && old('form_type') === 'tambah')

                <div style="
                    margin-bottom: 16px;
                    padding: 12px 14px;
                    background: #fff1f2;
                    border: 1px solid #fecdd3;
                    border-radius: 10px;
                    color: #be123c;
                    font-size: 13px;
                ">

                    <div style="font-weight: 700; margin-bottom: 6px;">
                        Data belum dapat disimpan
                    </div>

                    <ul style="margin: 0; padding-left: 18px;">

                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach

                    </ul>

                </div>

            @endif


            <input type="hidden"
                   name="form_type"
                   value="tambah">

            <input type="hidden"
                   name="id_kelas"
                   value="{{ $kelas_id ?? $kelas->id_kelas ?? '' }}">


            <!-- NAMA -->
            <div style="margin-bottom: 14px;">

                <label style="display: block; font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 6px;">
                    Nama Lengkap
                </label>

                <input type="text"
                       id="inputNamaSiswa"
                       name="nama_siswa"
                       value="{{ old('form_type') === 'tambah' ? old('nama_siswa') : '' }}"
                       placeholder="Masukkan nama siswa"
                       class="custom-input"
                       style="width: 100%; padding: 10px; background: #f8fafc; border-radius: 8px; font-size: 13px; outline: none; box-sizing: border-box;"
                       required>

            </div>


            <!-- NIS -->
            <div style="margin-bottom: 14px;">

                <label style="display: block; font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 6px;">
                    NIS
                </label>

                <input type="text"
                       id="inputNisSiswa"
                       name="nis"
                       value="{{ old('form_type') === 'tambah' ? old('nis') : '' }}"
                       placeholder="10123"
                       inputmode="numeric"
                       pattern="[0-9]*"
                       oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                       class="custom-input"
                       style="width: 100%; padding: 10px; background: #f8fafc; border-radius: 8px; font-size: 13px; outline: none; box-sizing: border-box;"
                       required>

                <!-- NOTE NIS DUPLIKAT -->
                <div id="nisDuplicateNote"
                     class="duplicate-note">

                    <span class="material-symbols-outlined"
                          style="font-size: 16px; flex-shrink: 0;">
                        error
                    </span>

                    <span>
                        NIS ini sudah terdaftar. Silakan gunakan NIS lain.
                    </span>

                </div>

            </div>


            <!-- JENIS KELAMIN -->
            <div style="margin-bottom: 20px;">

                <label style="display: block; font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 6px;">
                    Jenis Kelamin
                </label>

                <select id="selectJkSiswa"
                        name="jenis_kelamin"
                        class="custom-input"
                        style="width: 100%; padding: 10px; background: #f8fafc; border-radius: 8px; font-size: 13px; outline: none; box-sizing: border-box;"
                        required>

                    <option value="">
                        -- Pilih Jenis Kelamin --
                    </option>

                    <option value="L"
                        {{ old('form_type') === 'tambah' && old('jenis_kelamin') === 'L' ? 'selected' : '' }}>
                        Laki-laki
                    </option>

                    <option value="P"
                        {{ old('form_type') === 'tambah' && old('jenis_kelamin') === 'P' ? 'selected' : '' }}>
                        Perempuan
                    </option>

                </select>

            </div>


            <div style="display: flex; justify-content: flex-end; gap: 10px;">

                <button type="button"
                        onclick="closeModalTambah()"
                        style="padding: 8px 16px; border-radius: 8px; border: 1px solid #cbd5e1; background: #fff; color: #475569; font-weight: 600; font-size: 13px; cursor: pointer;">
                    Batal
                </button>

                <button type="submit"
                        id="btnSimpanMurid"
                        class="btn-animated"
                        style="padding: 8px 16px; border-radius: 8px; background: #1B234A; color: #fff; border: none; font-weight: 600; font-size: 13px; cursor: pointer;">
                    Simpan
                </button>

            </div>

        </form>

    </div>
</div>


<!-- =========================================================
     MODAL EDIT MURID
     ========================================================= -->

<div id="modalEditSiswa"
     class="modal-backdrop"
     style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.5); backdrop-filter: blur(4px); justify-content: center; align-items: center; z-index: 9999;">

    <div class="modal-content-animated"
         style="background: #ffffff; width: 100%; max-width: 480px; border-radius: 16px; padding: 24px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);">

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">

            <h3 style="margin: 0; font-size: 18px; font-weight: 700; color: #1e293b;">
                Edit Data Murid
            </h3>

            <button type="button"
                    onclick="closeModalEditSiswa()"
                    class="btn-icon-hover"
                    style="background: none; border: none; font-size: 20px; cursor: pointer; color: #64748b; padding: 2px 6px;">
                &times;
            </button>

        </div>


        <form id="formEditSiswa"
              action=""
              method="POST"
              onsubmit="return confirmVerifikasiEditSiswa(event)">

            @csrf
            @method('PUT')

            <input type="hidden"
                   name="form_type"
                   value="edit">

            <input type="hidden"
                   name="id_siswa"
                   id="editIdSiswa"
                   value="{{ old('form_type') === 'edit' ? old('id_siswa') : '' }}">


            <!-- ERROR KHUSUS EDIT -->
            @if ($errors->any() && old('form_type') === 'edit')

                <div style="
                    margin-bottom: 16px;
                    padding: 12px 14px;
                    background: #fff1f2;
                    border: 1px solid #fecdd3;
                    border-radius: 10px;
                    color: #be123c;
                    font-size: 13px;
                ">

                    <div style="font-weight: 700; margin-bottom: 6px;">
                        Data belum dapat diperbarui
                    </div>

                    <ul style="margin: 0; padding-left: 18px;">

                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach

                    </ul>

                </div>

            @endif


            <!-- NAMA -->
            <div style="margin-bottom: 14px;">

                <label style="display: block; font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 6px;">
                    Nama Lengkap
                </label>

                <input type="text"
                       id="editNamaSiswa"
                       name="nama_siswa"
                       value="{{ old('form_type') === 'edit' ? old('nama_siswa') : '' }}"
                       placeholder="Masukkan nama siswa"
                       class="custom-input"
                       style="width: 100%; padding: 10px; background: #f8fafc; border-radius: 8px; font-size: 13px; outline: none; box-sizing: border-box;"
                       required>

            </div>


            <!-- NIS -->
            <div style="margin-bottom: 14px;">

                <label style="display: block; font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 6px;">
                    NIS
                </label>

                <input type="text"
                       id="editNisSiswa"
                       name="nis"
                       value="{{ old('form_type') === 'edit' ? old('nis') : '' }}"
                       placeholder="10123"
                       inputmode="numeric"
                       pattern="[0-9]*"
                       oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                       class="custom-input"
                       style="width: 100%; padding: 10px; background: #f8fafc; border-radius: 8px; font-size: 13px; outline: none; box-sizing: border-box;"
                       required>

                <!-- NOTE NIS DUPLIKAT EDIT -->
                <div id="editNisDuplicateNote"
                     class="duplicate-note">

                    <span class="material-symbols-outlined"
                          style="font-size: 16px; flex-shrink: 0;">
                        error
                    </span>

                    <span>
                        NIS ini sudah digunakan oleh siswa lain.
                    </span>

                </div>

            </div>


            <!-- JENIS KELAMIN -->
            <div style="margin-bottom: 20px;">

                <label style="display: block; font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 6px;">
                    Jenis Kelamin
                </label>

                <select id="editJkSiswa"
                        name="jenis_kelamin"
                        class="custom-input"
                        style="width: 100%; padding: 10px; background: #f8fafc; border-radius: 8px; font-size: 13px; outline: none; box-sizing: border-box;"
                        required>

                    <option value="L"
                        {{ old('form_type') === 'edit' && old('jenis_kelamin') === 'L' ? 'selected' : '' }}>
                        Laki-laki
                    </option>

                    <option value="P"
                        {{ old('form_type') === 'edit' && old('jenis_kelamin') === 'P' ? 'selected' : '' }}>
                        Perempuan
                    </option>

                </select>

            </div>


            <div style="display: flex; justify-content: flex-end; gap: 10px;">

                <button type="button"
                        onclick="closeModalEditSiswa()"
                        style="padding: 8px 16px; border-radius: 8px; border: 1px solid #cbd5e1; background: #fff; color: #475569; font-weight: 600; font-size: 13px; cursor: pointer;">
                    Batal
                </button>

                <button type="submit"
                        class="btn-animated"
                        style="padding: 8px 16px; border-radius: 8px; background: #1B234A; color: #fff; border: none; font-weight: 600; font-size: 13px; cursor: pointer;">
                    Simpan Perubahan
                </button>

            </div>

        </form>

    </div>
</div>


<!-- =========================================================
     MODAL KUSTOM KONFIRMASI
     ========================================================= -->

<div id="modalConfirmAction"
     class="modal-backdrop"
     style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.5); backdrop-filter: blur(4px); justify-content: center; align-items: center; z-index: 10000;">

    <div class="modal-content-animated"
         style="background: #ffffff; width: 100%; max-width: 400px; border-radius: 16px; padding: 24px; text-align: center; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);">

        <div id="modalIconContainer"
             style="width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px auto; transition: transform 0.3s ease;">

            <span class="material-symbols-outlined"
                  id="modalConfirmIcon"
                  style="font-size: 24px;">
                warning
            </span>

        </div>

        <h3 id="modalConfirmTitle"
            style="margin: 0 0 8px 0; font-size: 18px; font-weight: 700; color: #1e293b;">
            Konfirmasi Action
        </h3>

        <p id="modalConfirmMessage"
           style="margin: 0 0 20px 0; font-size: 13px; color: #64748b; line-height: 1.5;">
        </p>

        <div style="display: flex; justify-content: center; gap: 12px;">

            <button type="button"
                    id="btnCancelConfirm"
                    onclick="closeModalConfirm()"
                    style="padding: 8px 20px; border-radius: 8px; border: 1px solid #cbd5e1; background: #fff; color: #475569; font-weight: 600; font-size: 13px; cursor: pointer;">
                Batal
            </button>

            <button type="button"
                    id="btnOkConfirm"
                    class="btn-animated"
                    style="padding: 8px 20px; border-radius: 8px; color: #fff; border: none; font-weight: 600; font-size: 13px; cursor: pointer;">
                Ya, Lanjutkan
            </button>

        </div>

    </div>
</div>


<script>

/* =========================================================
   STATE
   ========================================================= */

let sedangCekNis = false;


/* =========================================================
   MODAL TAMBAH MURID
   ========================================================= */

function openModalTambah() {

    document.getElementById('modalTambah').style.display = 'flex';

}

function closeModalTambah() {

    document.getElementById('modalTambah').style.display = 'none';

}


/* =========================================================
   MODAL EDIT SISWA
   ========================================================= */

function openModalEditSiswa(idSiswa, namaSiswa, nis, jenisKelamin) {

    const modal = document.getElementById('modalEditSiswa');
    const form = document.getElementById('formEditSiswa');

    form.action = `/admin/siswa/${idSiswa}`;

    document.getElementById('editIdSiswa').value = idSiswa;
    document.getElementById('editNamaSiswa').value = namaSiswa;
    document.getElementById('editNisSiswa').value = nis;
    document.getElementById('editJkSiswa').value = jenisKelamin;

    // Reset note duplikat ketika modal dibuka
    const note = document.getElementById('editNisDuplicateNote');
    const input = document.getElementById('editNisSiswa');

    note.classList.remove('show');
    input.classList.remove('input-error');

    modal.style.display = 'flex';

}

function closeModalEditSiswa() {

    document.getElementById('modalEditSiswa').style.display = 'none';

}


/* =========================================================
   MODAL EDIT WALI KELAS
   ========================================================= */

function openModalEditWali(idKelas, currentWaliId) {

    const modal = document.getElementById('modalEditWali');
    const form = document.getElementById('formEditWali');

    form.action = `/admin/kelas/${idKelas}`;

    if (currentWaliId) {

        const currentItem = document.querySelector(
            `.wali-item[data-id="${currentWaliId}"]`
        );

        if (currentItem) {

            selectWali(
                currentWaliId,
                currentItem.getAttribute('data-nama')
            );

        }

    } else {

        document.getElementById('hiddenWaliId').value = '';
        document.getElementById('inputSearchWali').value = '';

    }

    modal.style.display = 'flex';

}

function closeModalEditWali() {

    document.getElementById('modalEditWali').style.display = 'none';

    document.getElementById('dropdownWaliList').style.display = 'none';

}


/* =========================================================
   DROPDOWN WALI KELAS
   ========================================================= */

function showWaliList() {

    document.getElementById('dropdownWaliList').style.display = 'block';

    filterWaliList();

}

function filterWaliList() {

    const input = document
        .getElementById('inputSearchWali')
        .value
        .toLowerCase();

    const items = document.querySelectorAll('.wali-item');

    let count = 0;

    items.forEach(item => {

        const nama = item
            .getAttribute('data-nama')
            .toLowerCase();

        if (nama.includes(input)) {

            item.style.display = 'block';
            count++;

        } else {

            item.style.display = 'none';

        }

    });

    document.getElementById('noWaliFound').style.display =
        count === 0 ? 'block' : 'none';

}

function selectWali(id, nama) {

    document.getElementById('hiddenWaliId').value = id;

    document.getElementById('inputSearchWali').value = nama;

    document.getElementById('dropdownWaliList').style.display = 'none';

}


/* =========================================================
   MODAL CUSTOM CONFIRM
   ========================================================= */

function showCustomConfirm(title, message, isDanger, onConfirmCallback) {

    const modal = document.getElementById('modalConfirmAction');

    const iconContainer = document.getElementById('modalIconContainer');

    const icon = document.getElementById('modalConfirmIcon');

    const btnOk = document.getElementById('btnOkConfirm');


    document.getElementById('modalConfirmTitle').innerText = title;

    document.getElementById('modalConfirmMessage').innerText = message;


    if (isDanger) {

        iconContainer.style.backgroundColor = '#ffe4e6';

        icon.style.color = '#e11d48';

        icon.innerText = 'delete';

        btnOk.style.backgroundColor = '#e11d48';

    } else {

        iconContainer.style.backgroundColor = '#e0e7ff';

        icon.style.color = '#4338ca';

        icon.innerText = 'help_outline';

        btnOk.style.backgroundColor = '#1B234A';

    }


    btnOk.onclick = function() {

        closeModalConfirm();

        if (onConfirmCallback) {

            onConfirmCallback();

        }

    };


    modal.style.display = 'flex';

}


function closeModalConfirm() {

    document.getElementById('modalConfirmAction').style.display = 'none';

}


/* =========================================================
   KONFIRMASI HAPUS KELAS
   ========================================================= */

function confirmDeleteKelas(namaKelas) {

    showCustomConfirm(

        'Hapus Kelas',

        `Apakah Anda yakin ingin menghapus kelas "${namaKelas}"? Seluruh data siswa terkait juga akan terhapus.`,

        true,

        function() {

            document
                .getElementById('formDeleteKelas')
                .submit();

        }

    );

}


/* =========================================================
   KONFIRMASI HAPUS SISWA
   ========================================================= */

function confirmDeleteSiswa(idSiswa, namaSiswa) {

    showCustomConfirm(

        'Hapus Siswa',

        `Apakah Anda yakin ingin menghapus data siswa "${namaSiswa}"?`,

        true,

        function() {

            document
                .getElementById(`formDeleteSiswa-${idSiswa}`)
                .submit();

        }

    );

}


/* =========================================================
   VERIFIKASI UBAH WALI KELAS
   ========================================================= */

function confirmVerifikasiWali(event) {

    event.preventDefault();

    const namaGuru =
        document.getElementById('inputSearchWali').value;

    const idGuru =
        document.getElementById('hiddenWaliId').value;


    if (!idGuru) {

        alert(
            'Silakan pilih guru dari daftar pilihan yang tersedia!'
        );

        return false;

    }


    showCustomConfirm(

        'Verifikasi Perubahan Wali Kelas',

        `Apakah Anda yakin ingin mengubah Wali Kelas menjadi "${namaGuru}"?`,

        false,

        function() {

            document
                .getElementById('formEditWali')
                .submit();

        }

    );

    return false;

}


/* =========================================================
   VERIFIKASI TAMBAH MURID
   ========================================================= */

async function confirmVerifikasiTambah(event) {

    event.preventDefault();


    const form = document.getElementById('formTambahMurid');

    const namaSiswa =
        document.getElementById('inputNamaSiswa').value.trim();

    const nis =
        document.getElementById('inputNisSiswa').value.trim();

    const jenisKelamin =
        document.getElementById('selectJkSiswa').value;

    const inputNis =
        document.getElementById('inputNisSiswa');

    const duplicateNote =
        document.getElementById('nisDuplicateNote');

    const button =
        document.getElementById('btnSimpanMurid');


    // Bersihkan note sebelumnya
    duplicateNote.classList.remove('show');

    inputNis.classList.remove('input-error');


    // Validasi input kosong
    if (!namaSiswa || !nis || !jenisKelamin) {

        return false;

    }


    // Cegah klik berkali-kali saat request berlangsung
    if (sedangCekNis) {

        return false;

    }


    sedangCekNis = true;


    // Disable tombol sementara
    button.disabled = true;

    button.style.opacity = '0.7';

    button.style.cursor = 'not-allowed';


    try {

        /*
         * Cek NIS ke server.
         *
         * Bukan menggunakan $siswas yang sedang tampil
         * karena $siswas menggunakan pagination.
         */

        const response = await fetch(
            `{{ route('admin.siswa.checkNis') }}?nis=${encodeURIComponent(nis)}`
        );


        if (!response.ok) {

            throw new Error('Gagal mengecek NIS');

        }


        const data = await response.json();


        /*
         * =====================================================
         * NIS SUDAH TERDAFTAR
         * =====================================================
         *
         * Hanya tampilkan note merah.
         *
         * JANGAN tampilkan modal verifikasi.
         */

        if (data.exists) {

            duplicateNote.classList.add('show');

            inputNis.classList.add('input-error');

            inputNis.focus();

            sedangCekNis = false;

            button.disabled = false;

            button.style.opacity = '1';

            button.style.cursor = 'pointer';

            return false;

        }


        /*
         * =====================================================
         * NIS BELUM TERDAFTAR
         * =====================================================
         *
         * Baru tampilkan modal verifikasi.
         */

        sedangCekNis = false;

        button.disabled = false;

        button.style.opacity = '1';

        button.style.cursor = 'pointer';


        showCustomConfirm(

            'Verifikasi Tambah Murid',

            `Apakah Anda yakin data murid "${namaSiswa}" (NIS: ${nis}) sudah benar dan ingin disimpan?`,

            false,

            function() {

                form.submit();

            }

        );

    } catch (error) {

        console.error('Gagal mengecek NIS:', error);

        sedangCekNis = false;

        button.disabled = false;

        button.style.opacity = '1';

        button.style.cursor = 'pointer';

        alert(
            'Gagal mengecek NIS. Silakan coba lagi.'
        );

    }


    return false;

}


/* =========================================================
   VERIFIKASI EDIT SISWA
   ========================================================= */

async function confirmVerifikasiEditSiswa(event) {

    event.preventDefault();


    const form =
        document.getElementById('formEditSiswa');

    const idSiswa =
        document.getElementById('editIdSiswa').value;

    const namaSiswa =
        document.getElementById('editNamaSiswa').value.trim();

    const nis =
        document.getElementById('editNisSiswa').value.trim();

    const inputNis =
        document.getElementById('editNisSiswa');

    const duplicateNote =
        document.getElementById('editNisDuplicateNote');


    duplicateNote.classList.remove('show');

    inputNis.classList.remove('input-error');


    if (!namaSiswa || !nis) {

        return false;

    }


    if (sedangCekNis) {

        return false;

    }


    sedangCekNis = true;


    try {

        /*
         * Saat EDIT, ID siswa yang sedang diedit
         * dikirim supaya dirinya sendiri tidak dianggap duplikat.
         */

        const response = await fetch(
            `{{ route('admin.siswa.checkNis') }}?nis=${encodeURIComponent(nis)}&exclude_id=${encodeURIComponent(idSiswa)}`
        );


        if (!response.ok) {

            throw new Error('Gagal mengecek NIS');

        }


        const data = await response.json();


        /*
         * Kalau NIS digunakan siswa lain,
         * langsung tampilkan note merah.
         */

        if (data.exists) {

            duplicateNote.classList.add('show');

            inputNis.classList.add('input-error');

            inputNis.focus();

            sedangCekNis = false;

            return false;

        }


        /*
         * Kalau aman, baru tampilkan verifikasi.
         */

        sedangCekNis = false;


        showCustomConfirm(

            'Verifikasi Perubahan Siswa',

            `Apakah Anda yakin ingin menyimpan perubahan data untuk "${namaSiswa}"?`,

            false,

            function() {

                form.submit();

            }

        );

    } catch (error) {

        console.error('Gagal mengecek NIS:', error);

        sedangCekNis = false;

        alert(
            'Gagal mengecek NIS. Silakan coba lagi.'
        );

    }


    return false;

}


/* =========================================================
   CLICK DI LUAR DROPDOWN WALI
   ========================================================= */

document.addEventListener('click', function(e) {

    const searchInput =
        document.getElementById('inputSearchWali');

    const dropdown =
        document.getElementById('dropdownWaliList');


    if (searchInput && dropdown) {

        if (
            !searchInput.contains(e.target) &&
            !dropdown.contains(e.target)
        ) {

            dropdown.style.display = 'none';

        }

    }

});


/* =========================================================
   ESC UNTUK TUTUP MODAL
   ========================================================= */

document.addEventListener('keydown', function(e) {

    if (e.key === 'Escape') {

        closeModalTambah();

        closeModalEditSiswa();

        closeModalEditWali();

        closeModalConfirm();

    }

});


/* =========================================================
   BUKA KEMBALI MODAL SETELAH VALIDASI LARAVEL GAGAL
   ========================================================= */

document.addEventListener('DOMContentLoaded', function() {


    /*
     * Jika error berasal dari FORM TAMBAH
     */

    @if ($errors->any() && old('form_type') === 'tambah')

        openModalTambah();

    @endif


    /*
     * Jika error berasal dari FORM EDIT
     */

    @if ($errors->any() && old('form_type') === 'edit')

        openModalEditSiswa(

            @json(old('id_siswa')),

            @json(old('nama_siswa')),

            @json(old('nis')),

            @json(old('jenis_kelamin'))

        );

    @endif

});

</script>

@endsection

