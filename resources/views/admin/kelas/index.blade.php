@extends('layouts.admin')

@section('title', 'Manajemen Kelas')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .table-kelas-row {
        background-color: #ffffff;
        border-radius: 10px;
        transition: all 0.2s ease-in-out;
    }

    .table-kelas-row:hover {
        background-color: #f0f3ff !important;
    }

    .table-kelas-row:hover .col-id {
        border-left: 4px solid #1B234A !important;
        color: #1B234A !important;
    }

    .table-kelas-row:hover .action-btn {
        background-color: #1B234A !important;
        color: #ffffff !important;

        .swal2-container {
        z-index: 10000 !important;
    }
    }
</style>

<div class="header-action-wrapper" style="display: flex; justify-content: flex-end; align-items: center; margin-bottom: 20px;">
    <button type="button" onclick="openModalKelas()" class="btn-primary" style="background-color: #1B234A; color: #fff; padding: 10px 18px; border-radius: 10px; border: none; font-weight: 600; font-size: 13px; display: inline-flex; align-items: center; gap: 8px; cursor: pointer;">
        <span class="material-symbols-outlined" style="font-size: 18px;">person_add</span>
        Tambah Kelas
    </button>
</div>

<div class="activity-card" style="background: #ffffff; border-radius: 16px; padding: 24px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
    
    <div class="activity-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding: 0;">
        <h3 class="activity-title" style="margin: 0; font-size: 18px; font-weight: 700; color: #1e293b;">Direktori Kelas</h3>
        
        <div class="filters" style="display: flex; gap: 10px; align-items: center; margin: 0;">
<form action="{{ url()->current() }}" method="GET" style="display: inline-block;">
    <div class="search-box" style="position: relative; display: flex; align-items: center;">
        <span class="material-symbols-outlined" style="position: absolute; left: 10px; color: #94a3b8; font-size: 18px;">search</span>
        <input 
            type="text" 
            name="search" 
            value="{{ request('search') }}" 
            placeholder="Cari kelas atau wali..." 
            style="padding: 8px 12px 8px 36px; background: #f1f5f9; border: none; border-radius: 10px; font-size: 12px; outline: none; width: 220px;"
            onkeydown="if(event.key === 'Enter') this.form.submit();"
        >
    </div>
</form>
<!--             
            <button class="filter-btn" style="background: #f1f5f9; border: none; padding: 8px 12px; border-radius: 10px; cursor: pointer; display: flex; align-items: center;">
                <span class="material-symbols-outlined" style="font-size: 18px; color: #64748b;">tune</span>
            </button> -->
        </div>
    </div>

    <div class="table-wrapper">
        <table style="width: 100%; border-collapse: separate; border-spacing: 0 8px;">
            <thead>
                <tr style="background-color: #f8fafc; color: #64748b; font-size: 11px; text-transform: uppercase;">
                    <th style="padding: 12px 16px; text-align: left; border-top-left-radius: 8px; border-bottom-left-radius: 8px;">ID</th>
                    <th style="padding: 12px 16px; text-align: left;">NAMA KELAS</th>
                    <th style="padding: 12px 16px; text-align: left;">WALI KELAS</th>
                    <th style="padding: 12px 16px; text-align: center;">SISWA</th>
                    <th style="padding: 12px 16px; text-align: center; border-top-right-radius: 8px; border-bottom-right-radius: 8px;"></th>
                </tr>
            </thead>
            @if ($errors->any())
    <div style="background-color: #fef2f2; border: 1px solid #fecaca; color: #dc2626; padding: 12px; border-radius: 8px; margin-bottom: 16px;">
        <ul style="margin: 0; padding-left: 20px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
            <tbody style="font-size: 13px;">
                @forelse($kelas as $item)
                <tr class="table-kelas-row">
                    <td class="col-id" style="padding: 16px; color: #64748b; font-weight: 700; border-top-left-radius: 10px; border-bottom-left-radius: 10px; border-left: 4px solid transparent; transition: all 0.2s;">
                        KLS-{{ str_pad($item->id_kelas ?? $item->id, 3, '0', STR_PAD_LEFT) }}
                    </td>
                    <td style="padding: 16px; color: #0f172a; font-weight: 700;">
                        {{ $item->nama_kelas }}
                    </td>
                      <td style="padding: 16px; color: #475569;">
                          {{ $item->waliKelas->nama_guru ?? '-' }}
                    </td>
                    <td style="padding: 16px; text-align: center; color: #475569;">
                        {{ $item->siswas_count ?? $item->siswas->count() ?? 0 }}
                    </td>
                    <td style="padding: 16px; text-align: center; border-top-right-radius: 10px; border-bottom-right-radius: 10px;">
                       <a href="{{ route('admin.kelas.siswa', ['kelas_id' => $item->id_kelas ?? $item->id]) }}" class="action-btn" style="color: #64748b; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; background: #f8fafc; border-radius: 50%; transition: all 0.2s;">
                        <span class="material-symbols-outlined" style="font-size: 18px;">chevron_right</span>
                       </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding: 24px; text-align: center; color: #94a3b8;">
                        Belum ada data kelas.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
<!-- Style Tambahan untuk Kebersihan Z-Index SweetAlert2 -->
<style>
    .swal2-container {
        z-index: 10000 !important;
    }
</style>

<!-- POP UP MODAL (BLUR BACKGROUND) -->
<div id="modalTambahKelas" class="modal-overlay" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); z-index: 1050; justify-content: center; align-items: center; padding: 20px;">
    <div class="modal-card" style="background: #ffffff; width: 100%; max-width: 480px; border-radius: 16px; padding: 24px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 8px 10px -6px rgba(0,0,0,0.1);">
        
        <!-- Header Modal -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
            <div>
                <h3 style="margin: 0; font-size: 18px; font-weight: 700; color: #0f172a;">Tambah Kelas Baru</h3>
                <p style="margin: 4px 0 0; font-size: 12px; color: #64748b;">Isi tingkat, jurusan, dan pilih wali kelas yang bertugas.</p>
            </div>
            <button type="button" onclick="closeModalKelas()" style="background: transparent; border: none; color: #94a3b8; cursor: pointer; font-size: 20px;">&times;</button>
        </div>

        <!-- NOTIFIKASI PERINGATAN (DI ATAS FORM) -->
        <div id="modalAlert" style="display: none; background-color: #fef2f2; border: 1px solid #fecaca; color: #dc2626; padding: 10px 14px; border-radius: 8px; font-size: 12px; font-weight: 600; margin-bottom: 16px; align-items: center; gap: 8px;">
            <span class="material-symbols-outlined" style="font-size: 18px; color: #dc2626;">error</span>
            <span>Harap lengkapi semua bidang yang wajib diisi!</span>
        </div>

        <!-- Form Tambah Kelas -->
        <form id="formTambahKelas" action="{{ route('admin.kelas.store') }}" method="POST">
            @csrf

<!-- Preview ID Kelas -->
<div style="margin-bottom: 16px;">
    <label style="display: block; font-size: 12px; font-weight: 600; color: #475569; margin-bottom: 6px;">ID Kelas (Otomatis)</label>
    
    <!-- Input Tampilan (Disabled/Readonly) -->
    <input type="text" 
           value="KLS-{{ str_pad(($nextId ?? 1), 3, '0', STR_PAD_LEFT) }}" 
           readonly 
           style="width: 100%; padding: 10px 14px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 13px; font-weight: 700; color: #1B234A; outline: none; cursor: not-allowed; box-sizing: border-box;">
    <input type="hidden" name="id_kelas" value="{{ $nextId ?? 1 }}">
</div>
            <!-- Input Tingkat & Jurusan (Grid 2 Kolom) -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #475569; margin-bottom: 6px;">Tingkat <span style="color: #ef4444;">*</span></label>
                    <select name="tingkat_kelas" id="tingkat_kelas" onchange="clearError('tingkat_kelas')" style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px; outline: none; background: #fff; box-sizing: border-box;">
                        <option value="">-- Pilih --</option>
                        <option value="X">X</option>
                        <option value="XI">XI</option>
                        <option value="XII">XII</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #475569; margin-bottom: 6px;">Jurusan & Nomor <span style="color: #ef4444;">*</span></label>
                    <input type="text" name="jurusan_kelas" id="jurusan_kelas" placeholder="Contoh: RPL 1" oninput="clearError('jurusan_kelas')" style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px; outline: none; box-sizing: border-box;">
                </div>
            </div>

            <!-- Input Hidden untuk dikirim ke Controller sebagai nama_kelas -->
            <input type="hidden" name="nama_kelas" id="nama_kelas">

            <!-- Select Wali Kelas -->
        <div style="margin-bottom: 24px;">
    <label style="display: block; font-size: 12px; font-weight: 600; color: #475569; margin-bottom: 6px;">Wali Kelas <span style="color: #ef4444;">*</span></label>
    <select name="wali_kelas" id="wali_kelas" placeholder="Cari nama guru..." autocomplete="off">
        <option value="">-- Pilih Wali Kelas --</option>
        @foreach($gurus as $guru)
            <option value="{{ $guru->id_guru }}">{{ $guru->nama_guru ?? $guru->nama }}</option>
        @endforeach
    </select>
</div>

            <!-- Action Buttons -->
            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="closeModalKelas()" style="padding: 10px 18px; background: #f1f5f9; color: #475569; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 13px;">Batal</button>
                <button type="button" onclick="konfirmasiSimpan(event)" style="padding: 10px 18px; background: #1B234A; color: #ffffff; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 13px;">Simpan Kelas</button>
            </div>
        </form>
    </div>
</div>
<script>
    let guruSelect;

    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi TomSelect untuk dropdown Wali Kelas
        guruSelect = new TomSelect('#wali_kelas', {
            create: false,
            sortField: {
                field: "text",
                direction: "asc"
            },
            placeholder: "Ketik nama guru..."
        });
    });

    function openModalKelas() {
        document.getElementById('modalTambahKelas').style.display = 'flex';
        resetFormValidation();
        if (guruSelect) guruSelect.clear();
    }

    function closeModalKelas() {
        document.getElementById('modalTambahKelas').style.display = 'none';
        resetFormValidation();
    }

    function clearError(id) {
        document.getElementById(id).style.borderColor = '#cbd5e1';
        const tingkat = document.getElementById('tingkat_kelas').value;
        const jurusan = document.getElementById('jurusan_kelas').value.trim();
        const wali = document.getElementById('wali_kelas').value;

        if (tingkat && jurusan && wali) {
            document.getElementById('modalAlert').style.display = 'none';
        }
    }

    function resetFormValidation() {
        document.getElementById('modalAlert').style.display = 'none';
        document.getElementById('tingkat_kelas').style.borderColor = '#cbd5e1';
        document.getElementById('jurusan_kelas').style.borderColor = '#cbd5e1';
        if (guruSelect) {
            guruSelect.wrapper.style.borderColor = '#cbd5e1';
        }
    }

    function konfirmasiSimpan(e) {
        if (e) e.preventDefault();

        const tingkat = document.getElementById('tingkat_kelas');
        const jurusan = document.getElementById('jurusan_kelas');
        const selectWali = document.getElementById('wali_kelas');
        const alertBox = document.getElementById('modalAlert');

        let isValid = true;

        if (!tingkat.value) {
            tingkat.style.borderColor = '#ef4444';
            isValid = false;
        }

        if (!jurusan.value.trim()) {
            jurusan.style.borderColor = '#ef4444';
            isValid = false;
        }

        if (!selectWali.value) {
            if (guruSelect) guruSelect.wrapper.style.borderColor = '#ef4444';
            isValid = false;
        }

        if (!isValid) {
            alertBox.style.display = 'flex';
            return;
        }

        alertBox.style.display = 'none';

        // Gabungkan tingkat + jurusan menjadi nama_kelas (misal: "X RPL 1")
        const fullNamaKelas = `${tingkat.value} ${jurusan.value.trim()}`;
        document.getElementById('nama_kelas').value = fullNamaKelas;

        // Pop-up SweetAlert2
        Swal.fire({
            title: 'Verifikasi Simpan Data',
            text: `Apakah Anda yakin ingin menambahkan kelas "${fullNamaKelas}"?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#1B234A',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Ya, Simpan!',
            cancelButtonText: 'Cek Kembali'
        }).then((result) => {
            if (result.isConfirmed) {
                closeModalKelas();
                document.getElementById('formTambahKelas').submit();
            }
        });
    }
</script>
@endsection