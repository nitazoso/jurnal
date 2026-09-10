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
            </div>
        </div>
    </div>

    <!-- Group Tombol Aksi Kanan -->
    <div style="display: flex; gap: 12px; align-items: center;">
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
        <form action="{{ route('admin.siswa.index') }}" method="GET" style="position: relative; display: flex; align-items: center; width: 320px;">
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
                    <!-- Nomor Presensi Format 01, 02, dst -->
                    <td style="padding: 16px; text-align: center; color: #1B234A; font-weight: 700;">
                        {{ str_pad($siswa->no_presensi, 2, '0', STR_PAD_LEFT) }}
                    </td>
                    <td style="padding: 16px;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 32px; height: 32px; background-color: #2b386b; color: #ffffff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 12px;">
                                {{ strtoupper(substr($siswa->nama_siswa ?? 'S', 0, 1)) }}
                            </div>
                            <!-- DIBERESKAN: nama -> nama_siswa -->
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
                            <!-- Tombol Edit -->
                            <a href="{{ route('admin.siswa.edit', $siswa->id_siswa) }}" style="color: #3b82f6; text-decoration: none; padding: 4px; display: flex; align-items: center;">
                                <span class="material-symbols-outlined" style="font-size: 18px;">edit</span>
                            </a>

                            <!-- Tombol Hapus -->
                            <form action="{{ route('admin.siswa.destroy', $siswa->id_siswa) }}" method="POST" onsubmit="return confirm('Hapus siswa ini?')" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: none; border: none; color: #ef4444; cursor: pointer; padding: 4px; display: flex; align-items: center;">
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

<!-- MODAL POPUP TAMBAH MURID -->
<div id="modalTambah" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.5); backdrop-filter: blur(4px); justify-content: center; align-items: center; z-index: 9999;">
    <div style="background: #ffffff; width: 100%; max-width: 480px; border-radius: 16px; padding: 24px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <h3 style="margin: 0; font-size: 18px; font-weight: 700; color: #1e293b;">Tambah Murid Baru</h3>
            <button onclick="closeModalTambah()" style="background: none; border: none; font-size: 20px; cursor: pointer; color: #64748b;">&times;</button>
        </div>

        <form action="{{ route('admin.siswa.store') }}" method="POST">
            @csrf
            <input type="hidden" name="id_kelas" value="{{ $kelas_id ?? $kelas->id_kelas ?? '' }}">

            <div style="margin-bottom: 14px;">
                <label style="display: block; font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 6px;">Nama Lengkap</label>
                <!-- DIBERESKAN: name="nama" -> name="nama_siswa" -->
                <input type="text" name="nama_siswa" placeholder="Masukkan nama siswa" style="width: 100%; padding: 10px; background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px; outline: none; box-sizing: border-box;" required>
            </div>

            <div style="margin-bottom: 14px;">
                <label style="display: block; font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 6px;">NIS</label>
                <input type="text" name="nis" placeholder="10123" style="width: 100%; padding: 10px; background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px; outline: none; box-sizing: border-box;" required>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 6px;">Jenis Kelamin</label>
                <select name="jenis_kelamin" style="width: 100%; padding: 10px; background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px; outline: none; box-sizing: border-box;" required>
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

<script>
    function openModalTambah() {
        document.getElementById('modalTambah').style.display = 'flex';
    }
    function closeModalTambah() {
        document.getElementById('modalTambah').style.display = 'none';
    }
</script>
@endsection