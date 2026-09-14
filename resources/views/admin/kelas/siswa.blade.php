@extends('layouts.admin')

@section('title', 'Manajemen Murid')

@section('content')
<!-- Top Action / Header Detail Kelas -->
<div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px;">
    <div style="display: flex; align-items: flex-start; gap: 16px;">
        <!-- Tombol Kembali -->
        <a href="{{ route('admin.kelas.index') }}" style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; background-color: #f1f5f9; border-radius: 50%; color: #334155; text-decoration: none; margin-top: 4px;">
            <span class="material-symbols-outlined" style="font-size: 20px;">arrow_back</span>
        </a>

        <div>
            <span style="font-size: 11px; font-weight: 700; color: #64748b; letter-spacing: 0.5px; text-transform: uppercase;">MANAJEMEN MURID</span>
            <h2 style="margin: 2px 0 10px 0; font-size: 26px; font-weight: 800; color: #1e293b;">{{ $kelas->nama_kelas ?? 'Detail Kelas' }}</h2>
            
            <!-- Badge Wali Kelas -->
            <div style="display: inline-flex; align-items: center; gap: 8px; background-color: #f1f5f9; padding: 6px 14px; border-radius: 10px; font-size: 13px; color: #475569; font-weight: 500;">
                <span class="material-symbols-outlined" style="font-size: 18px; color: #64748b;">assignment_ind</span>
                <span>Wali Kelas: <strong>{{ $kelas->waliKelas->nama_guru ?? $kelas->waliKelas->nama ?? '-' }}</strong></span>
                
                <!-- Tombol Pensil Edit Wali -->
                <button type="button" 
                        onclick="openModalEditWali('{{ $kelas->id_kelas }}', '{{ $kelas->wali_kelas }}')" 
                        style="background: none; border: none; cursor: pointer; padding: 2px; display: inline-flex; align-items: center; color: #4f46e5; border-radius: 4px; transition: background 0.2s;"
                        title="Edit Wali Kelas">
                    <span class="material-symbols-outlined" style="font-size: 16px;">edit</span>
                </button>
            </div>
        </div>
    </div>
           
    <!-- Group Tombol Aksi Kanan -->
    <div style="display: flex; gap: 12px; align-items: center;">
        <!-- Form Hapus Kelas -->
        <form id="formDeleteKelas" action="{{ route('admin.kelas.destroy', $kelas->id_kelas ?? $kelas->id ?? 1) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="button" onclick="confirmDeleteKelas('{{ $kelas->nama_kelas ?? 'Kelas Ini' }}')" style="display: inline-flex; align-items: center; gap: 8px; border: 1px solid #fecdd3; background-color: #fff1f2; color: #e11d48; padding: 10px 18px; border-radius: 10px; font-weight: 600; font-size: 13px; cursor: pointer;">
                <span class="material-symbols-outlined" style="font-size: 18px;">delete</span>
                Hapus Kelas
            </button>
        </form>

        <!-- Tombol Buka Modal Tambah Murid -->
        <button type="button" onclick="openModalTambah()" style="display: inline-flex; align-items: center; gap: 8px; background-color: #1B234A; color: #ffffff; padding: 10px 18px; border-radius: 10px; font-weight: 600; font-size: 13px; border: none; cursor: pointer;">
            <span class="material-symbols-outlined" style="font-size: 18px;">person_add</span>
            Tambah Murid
        </button>
    </div>
</div>

<!-- Card Utama Data Murid -->
<div class="activity-card" style="background: #ffffff; border-radius: 16px; padding: 24px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
    
    <!-- Filter Search & Total Murid -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <form action="{{ route('admin.kelas.siswa') }}" method="GET" style="position: relative; display: flex; align-items: center; width: 320px;">
            <input type="hidden" name="kelas_id" value="{{ $kelas_id ?? $kelas->id_kelas ?? '' }}">
            <span class="material-symbols-outlined" style="position: absolute; left: 12px; color: #94a3b8; font-size: 20px;">search</span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau NIS... (Tekan Enter)" style="width: 100%; padding: 10px 14px 10px 40px; background: #f1f5f9; border: none; border-radius: 10px; font-size: 13px; outline: none; color: #334155;">
        </form>

        <div style="font-size: 13px; color: #64748b; font-weight: 500;">
            Total: <span style="background-color: #e0e7ff; color: #3730a3; padding: 4px 12px; border-radius: 12px; font-weight: 700; margin-left: 6px;">{{ count($siswas ?? []) }} Siswa</span>
        </div>
    </div>

    <!-- Tabel Data Murid -->
    <div class="table-wrapper">
        <table style="width: 100%; border-collapse: separate; border-spacing: 0;">
            <thead>
                <tr style="background-color: #f8fafc; color: #64748b; font-size: 11px; text-transform: uppercase; font-weight: 700;">
                    <th style="padding: 14px 16px; text-align: center; width: 100px; border-top-left-radius: 10px; border-bottom-left-radius: 10px;">NO. PRESENSI</th>
                    <th style="padding: 14px 16px; text-align: left;">Nama Murid</th>
                    <th style="padding: 14px 16px; text-align: center;">NIS</th>
                    <th style="padding: 14px 16px; text-align: center;">Jenis Kelamin</th>
                    <th style="padding: 14px 16px; text-align: center; width: 100px; border-top-right-radius: 10px; border-bottom-right-radius: 10px;">Aksi</th>
                </tr>
            </thead>
            <tbody style="font-size: 13px;">
                @forelse($siswas ?? [] as $siswa)
                <tr style="border-bottom: 1px solid #f1f5f9;">
                    <td style="padding: 16px; text-align: center; color: #1B234A; font-weight: 700;">
                        {{ str_pad($siswa->no_presensi, 2, '0', STR_PAD_LEFT) }}
                    </td>
                    <td style="padding: 16px;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 32px; height: 32px; background-color: #2b386b; color: #ffffff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 12px;">
                                {{ strtoupper(substr($siswa->nama_siswa ?? 'S', 0, 1)) }}
                            </div>
                            <span style="font-weight: 700; color: #1e293b;">{{ $siswa->nama_siswa }}</span>
                        </div>
                    </td>
                    <td style="padding: 16px; text-align: center; color: #64748b; font-weight: 500;">
                        {{ $siswa->nis }} 
                    </td>
                    <td style="padding: 16px; text-align: center;">
                        @if(($siswa->jenis_kelamin ?? 'L') == 'L')
                            <span style="display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; background-color: #e0e7ff; color: #3730a3; border-radius: 50%;" title="Laki-laki">
                                <span class="material-symbols-outlined" style="font-size: 16px;">male</span>
                            </span>
                        @else
                            <span style="display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; background-color: #fce7f3; color: #9d174d; border-radius: 50%;" title="Perempuan">
                                <span class="material-symbols-outlined" style="font-size: 16px;">female</span>
                            </span>
                        @endif
                    </td>
                    <td style="padding: 16px; text-align: center;">
                        <div style="display: flex; justify-content: center; align-items: center; gap: 8px;">
                            <!-- Tombol Edit Siswa (Membuka Modal) -->
                            <button type="button" 
                                    onclick="openModalEditSiswa('{{ $siswa->id_siswa }}', '{{ addslashes($siswa->nama_siswa) }}', '{{ $siswa->nis }}', '{{ $siswa->jenis_kelamin }}')" 
                                    style="background: none; border: none; color: #3b82f6; cursor: pointer; padding: 4px; display: flex; align-items: center;"
                                    title="Edit Siswa">
                                <span class="material-symbols-outlined" style="font-size: 18px;">edit</span>
                            </button>

                            <!-- Tombol Hapus Siswa -->
                            <form id="formDeleteSiswa-{{ $siswa->id_siswa }}" action="{{ route('admin.siswa.destroy', $siswa->id_siswa) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmDeleteSiswa('{{ $siswa->id_siswa }}', '{{ addslashes($siswa->nama_siswa) }}')" style="background: none; border: none; color: #ef4444; cursor: pointer; padding: 4px; display: flex; align-items: center;" title="Hapus Siswa">
                                    <span class="material-symbols-outlined" style="font-size: 18px;">delete</span>
                                </button>
                            </form>
                        </div> 
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding: 32px; text-align: center; color: #94a3b8;">
                        Belum ada data siswa di kelas ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL EDIT WALI KELAS -->
<div id="modalEditWali" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.5); backdrop-filter: blur(4px); justify-content: center; align-items: center; z-index: 9999;">
    <div style="background: #ffffff; width: 100%; max-width: 440px; border-radius: 16px; padding: 24px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <h3 style="margin: 0; font-size: 18px; font-weight: 700; color: #1e293b;">Ubah Wali Kelas</h3>
            <button type="button" onclick="closeModalEditWali()" style="background: none; border: none; font-size: 20px; cursor: pointer; color: #64748b;">&times;</button>
        </div>

        <form id="formEditWali" action="" method="POST" onsubmit="return confirmVerifikasiWali(event)">
            @csrf
            @method('PUT')
            <input type="hidden" name="nama_kelas" value="{{ $kelas->nama_kelas }}">

            <div style="margin-bottom: 20px; position: relative;">
                <label style="display: block; font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 6px;">Cari / Pilih Wali Kelas</label>
                
                <input type="hidden" name="wali_kelas" id="hiddenWaliId" required>

                <input type="text" 
                       id="inputSearchWali" 
                       placeholder="Ketik nama guru..." 
                       onfocus="showWaliList()" 
                       oninput="filterWaliList()" 
                       autocomplete="off"
                       style="width: 100%; padding: 10px 12px; background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px; outline: none; box-sizing: border-box;" 
                       required>

                <div id="dropdownWaliList" style="display: none; position: absolute; left: 0; right: 0; top: 100%; margin-top: 4px; background: #ffffff; border: 1px solid #cbd5e1; border-radius: 8px; max-height: 180px; overflow-y: auto; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); z-index: 100;">
                    @foreach($gurus as $g)
                        @php $namaG = $g->nama_guru ?? $g->nama; @endphp
                        <div class="wali-item" 
                             data-id="{{ $g->id_guru }}" 
                             data-nama="{{ $namaG }}" 
                             onclick="selectWali('{{ $g->id_guru }}', '{{ $namaG }}')"
                             style="padding: 10px 12px; font-size: 13px; color: #334155; cursor: pointer; border-bottom: 1px solid #f1f5f9; transition: background 0.15s;">
                            {{ $namaG }}
                        </div>
                    @endforeach
                    <div id="noWaliFound" style="display: none; padding: 10px 12px; font-size: 13px; color: #94a3b8; text-align: center;">
                        Guru tidak ditemukan
                    </div>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="closeModalEditWali()" style="padding: 8px 16px; border-radius: 8px; border: 1px solid #cbd5e1; background: #fff; color: #475569; font-weight: 600; font-size: 13px; cursor: pointer;">Batal</button>
                <button type="submit" style="padding: 8px 16px; border-radius: 8px; background: #1B234A; color: #fff; border: none; font-weight: 600; font-size: 13px; cursor: pointer;">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL POPUP TAMBAH MURID -->
<div id="modalTambah" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.5); backdrop-filter: blur(4px); justify-content: center; align-items: center; z-index: 9999;">
    <div style="background: #ffffff; width: 100%; max-width: 480px; border-radius: 16px; padding: 24px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <h3 style="margin: 0; font-size: 18px; font-weight: 700; color: #1e293b;">Tambah Murid Baru</h3>
            <button onclick="closeModalTambah()" style="background: none; border: none; font-size: 20px; cursor: pointer; color: #64748b;">&times;</button>
        </div>

        <form id="formTambahMurid" action="{{ route('admin.siswa.store') }}" method="POST" onsubmit="return confirmVerifikasiTambah(event)">
            @csrf
            <input type="hidden" name="id_kelas" value="{{ $kelas_id ?? $kelas->id_kelas ?? '' }}">

            <div style="margin-bottom: 14px;">
                <label style="display: block; font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 6px;">Nama Lengkap</label>
                <input type="text" id="inputNamaSiswa" name="nama_siswa" placeholder="Masukkan nama siswa" style="width: 100%; padding: 10px; background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px; outline: none; box-sizing: border-box;" required>
            </div>

            <div style="margin-bottom: 14px;">
                <label style="display: block; font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 6px;">NIS</label>
                <input type="text" 
                       id="inputNisSiswa"
                       name="nis" 
                       placeholder="10123" 
                       inputmode="numeric" 
                       pattern="[0-9]*"
                       oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                       style="width: 100%; padding: 10px; background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px; outline: none; box-sizing: border-box;" 
                       required>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 6px;">Jenis Kelamin</label>
                <select id="selectJkSiswa" name="jenis_kelamin" style="width: 100%; padding: 10px; background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px; outline: none; box-sizing: border-box;" required>
                    <option value="">-- Pilih Jenis Kelamin --</option>
                    <option value="L">Laki-laki</option>
                    <option value="P">Perempuan</option>
                </select>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="closeModalTambah()" style="padding: 8px 16px; border-radius: 8px; border: 1px solid #cbd5e1; background: #fff; color: #475569; font-weight: 600; font-size: 13px; cursor: pointer;">Batal</button>
                <button type="submit" style="padding: 8px 16px; border-radius: 8px; background: #1B234A; color: #fff; border: none; font-weight: 600; font-size: 13px; cursor: pointer;">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL POPUP EDIT MURID -->
<div id="modalEditSiswa" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.5); backdrop-filter: blur(4px); justify-content: center; align-items: center; z-index: 9999;">
    <div style="background: #ffffff; width: 100%; max-width: 480px; border-radius: 16px; padding: 24px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <h3 style="margin: 0; font-size: 18px; font-weight: 700; color: #1e293b;">Edit Data Murid</h3>
            <button onclick="closeModalEditSiswa()" style="background: none; border: none; font-size: 20px; cursor: pointer; color: #64748b;">&times;</button>
        </div>

        <form id="formEditSiswa" action="" method="POST" onsubmit="return confirmVerifikasiEditSiswa(event)">
            @csrf
            @method('PUT')

            <div style="margin-bottom: 14px;">
                <label style="display: block; font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 6px;">Nama Lengkap</label>
                <input type="text" id="editNamaSiswa" name="nama_siswa" placeholder="Masukkan nama siswa" style="width: 100%; padding: 10px; background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px; outline: none; box-sizing: border-box;" required>
            </div>

            <div style="margin-bottom: 14px;">
                <label style="display: block; font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 6px;">NIS</label>
                <input type="text" 
                       id="editNisSiswa"
                       name="nis" 
                       placeholder="10123" 
                       inputmode="numeric" 
                       pattern="[0-9]*"
                       oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                       style="width: 100%; padding: 10px; background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px; outline: none; box-sizing: border-box;" 
                       required>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 6px;">Jenis Kelamin</label>
                <select id="editJkSiswa" name="jenis_kelamin" style="width: 100%; padding: 10px; background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px; outline: none; box-sizing: border-box;" required>
                    <option value="L">Laki-laki</option>
                    <option value="P">Perempuan</option>
                </select>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="closeModalEditSiswa()" style="padding: 8px 16px; border-radius: 8px; border: 1px solid #cbd5e1; background: #fff; color: #475569; font-weight: 600; font-size: 13px; cursor: pointer;">Batal</button>
                <button type="submit" style="padding: 8px 16px; border-radius: 8px; background: #1B234A; color: #fff; border: none; font-weight: 600; font-size: 13px; cursor: pointer;">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL KUSTOM KONFIRMASI HAPUS & VERIFIKASI -->
<div id="modalConfirmAction" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.5); backdrop-filter: blur(4px); justify-content: center; align-items: center; z-index: 10000;">
    <div style="background: #ffffff; width: 100%; max-width: 400px; border-radius: 16px; padding: 24px; text-align: center; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);">
        <div id="modalIconContainer" style="width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px auto;">
            <span class="material-symbols-outlined" id="modalConfirmIcon" style="font-size: 24px;">warning</span>
        </div>
        <h3 id="modalConfirmTitle" style="margin: 0 0 8px 0; font-size: 18px; font-weight: 700; color: #1e293b;">Konfirmasi Action</h3>
        <p id="modalConfirmMessage" style="margin: 0 0 20px 0; font-size: 13px; color: #64748b; line-height: 1.5;"></p>
        
        <div style="display: flex; justify-content: center; gap: 12px;">
            <button type="button" id="btnCancelConfirm" onclick="closeModalConfirm()" style="padding: 8px 20px; border-radius: 8px; border: 1px solid #cbd5e1; background: #fff; color: #475569; font-weight: 600; font-size: 13px; cursor: pointer;">Batal</button>
            <button type="button" id="btnOkConfirm" style="padding: 8px 20px; border-radius: 8px; color: #fff; border: none; font-weight: 600; font-size: 13px; cursor: pointer;">Ya, Lanjutkan</button>
        </div>
    </div>
</div>

<style>
    .wali-item:hover {
        background-color: #f0f9ff;
        color: #0284c7 !important;
    }
</style>

<script>
    function openModalTambah() {
        document.getElementById('modalTambah').style.display = 'flex';
    }
    function closeModalTambah() {
        document.getElementById('modalTambah').style.display = 'none';
    }

    // Modal Edit Siswa
    function openModalEditSiswa(idSiswa, namaSiswa, nis, jenisKelamin) {
        const modal = document.getElementById('modalEditSiswa');
        const form = document.getElementById('formEditSiswa');

        form.action = `/admin/siswa/${idSiswa}`;
        document.getElementById('editNamaSiswa').value = namaSiswa;
        document.getElementById('editNisSiswa').value = nis;
        document.getElementById('editJkSiswa').value = jenisKelamin;

        modal.style.display = 'flex';
    }

    function closeModalEditSiswa() {
        document.getElementById('modalEditSiswa').style.display = 'none';
    }

    // Modal Edit Wali Kelas
    function openModalEditWali(idKelas, currentWaliId) {
        const modal = document.getElementById('modalEditWali');
        const form = document.getElementById('formEditWali');

        form.action = `/admin/kelas/${idKelas}`;

        if (currentWaliId) {
            const currentItem = document.querySelector(`.wali-item[data-id="${currentWaliId}"]`);
            if (currentItem) {
                selectWali(currentWaliId, currentItem.getAttribute('data-nama'));
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

    function showWaliList() {
        document.getElementById('dropdownWaliList').style.display = 'block';
        filterWaliList();
    }

    function filterWaliList() {
        const input = document.getElementById('inputSearchWali').value.toLowerCase();
        const items = document.querySelectorAll('.wali-item');
        let count = 0;

        items.forEach(item => {
            const nama = item.getAttribute('data-nama').toLowerCase();
            if (nama.includes(input)) {
                item.style.display = 'block';
                count++;
            } else {
                item.style.display = 'none';
            }
        });

        document.getElementById('noWaliFound').style.display = count === 0 ? 'block' : 'none';
    }

    function selectWali(id, nama) {
        document.getElementById('hiddenWaliId').value = id;
        document.getElementById('inputSearchWali').value = nama;
        document.getElementById('dropdownWaliList').style.display = 'none';
    }

    // Modal Kustom Konfirmasi UI (Menggantikan Alert Bawaan Browser / localhost)
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
            if (onConfirmCallback) onConfirmCallback();
        };

        modal.style.display = 'flex';
    }

    function closeModalConfirm() {
        document.getElementById('modalConfirmAction').style.display = 'none';
    }

    // Konfirmasi Hapus Kelas
    function confirmDeleteKelas(namaKelas) {
        showCustomConfirm(
            'Hapus Kelas',
            `Apakah Anda yakin ingin menghapus kelas "${namaKelas}"? Seluruh data siswa terkait juga akan terhapus.`,
            true,
            function() {
                document.getElementById('formDeleteKelas').submit();
            }
        );
    }

    // Konfirmasi Hapus Siswa
    function confirmDeleteSiswa(idSiswa, namaSiswa) {
        showCustomConfirm(
            'Hapus Siswa',
            `Apakah Anda yakin ingin menghapus data siswa "${namaSiswa}"?`,
            true,
            function() {
                document.getElementById(`formDeleteSiswa-${idSiswa}`).submit();
            }
        );
    }

    // Verifikasi Ubah Wali Kelas
    function confirmVerifikasiWali(event) {
        event.preventDefault();
        const namaGuru = document.getElementById('inputSearchWali').value;
        const idGuru = document.getElementById('hiddenWaliId').value;

        if (!idGuru) {
            alert('Silakan pilih guru dari daftar pilihan yang tersedia!');
            return false;
        }

        showCustomConfirm(
            'Verifikasi Perubahan Wali Kelas',
            `Apakah Anda yakin ingin mengubah Wali Kelas menjadi "${namaGuru}"?`,
            false,
            function() {
                document.getElementById('formEditWali').submit();
            }
        );
        return false;
    }

    // Verifikasi Tambah Murid Baru
    function confirmVerifikasiTambah(event) {
        event.preventDefault();
        const namaSiswa = document.getElementById('inputNamaSiswa').value;
        const nis = document.getElementById('inputNisSiswa').value;

        if (!namaSiswa || !nis) {
            return false;
        }

        showCustomConfirm(
            'Verifikasi Tambah Murid',
            `Apakah Anda yakin data murid "${namaSiswa}" (NIS: ${nis}) sudah benar dan ingin disimpan?`,
            false,
            function() {
                document.getElementById('formTambahMurid').submit();
            }
        );
        return false;
    }

    // Verifikasi Edit Siswa
    function confirmVerifikasiEditSiswa(event) {
        event.preventDefault();
        const namaSiswa = document.getElementById('editNamaSiswa').value;
        const nis = document.getElementById('editNisSiswa').value;

        if (!namaSiswa || !nis) {
            return false;
        }

        showCustomConfirm(
            'Verifikasi Perubahan Siswa',
            `Apakah Anda yakin ingin menyimpan perubahan data untuk "${namaSiswa}"?`,
            false,
            function() {
                document.getElementById('formEditSiswa').submit();
            }
        );
        return false;
    }

    // Sembunyikan Dropdown jika Klik di Luar Box
    document.addEventListener('click', function(e) {
        const searchInput = document.getElementById('inputSearchWali');
        const dropdown = document.getElementById('dropdownWaliList');

        if (searchInput && dropdown) {
            if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.style.display = 'none';
            }
        }
    });
</script>
@endsection