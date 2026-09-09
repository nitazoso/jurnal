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
            <h2 style="margin: 2px 0 10px 0; font-size: 26px; font-weight: 800; color: #1e293b;">{{ $kelas->nama_kelas ?? 'X MIPA 1' }}</h2>
            
            <!-- Badge Wali Kelas -->
            <div style="display: inline-flex; align-items: center; gap: 8px; background-color: #f1f5f9; padding: 6px 14px; border-radius: 10px; font-size: 13px; color: #475569; font-weight: 500;">
                <span class="material-symbols-outlined" style="font-size: 18px; color: #64748b;">assignment_ind</span>
                <span>Wali Kelas: <strong>{{ $kelas->waliKelas->name ?? 'Dra. Hj. Nurhayati, M.Pd' }}</strong></span>
                <a href="{{ route('admin.kelas.edit', $kelas->id_kelas ?? $kelas->id ?? 1) }}" style="color: #64748b; display: flex; align-items: center; text-decoration: none; margin-left: 2px;">
                    <span class="material-symbols-outlined" style="font-size: 16px;">edit</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Group Tombol Aksi Kanan -->
    <div style="display: flex; gap: 12px; align-items: center;">
        <form action="{{ route('admin.kelas.destroy', $kelas->id_kelas ?? $kelas->id ?? 1) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kelas ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" style="display: inline-flex; align-items: center; gap: 8px; border: 1px solid #fecdd3; background-color: #fff1f2; color: #e11d48; padding: 10px 18px; border-radius: 10px; font-weight: 600; font-size: 13px; cursor: pointer;">
                <span class="material-symbols-outlined" style="font-size: 18px;">delete</span>
                Hapus Kelas
            </button>
        </form>

        <a href="{{ route('admin.siswa.create', ['kelas_id' => $kelas->id_kelas ?? $kelas->id ?? 1]) }}" style="display: inline-flex; align-items: center; gap: 8px; background-color: #1B234A; color: #ffffff; padding: 10px 18px; border-radius: 10px; text-decoration: none; font-weight: 600; font-size: 13px;">
            <span class="material-symbols-outlined" style="font-size: 18px;">person_add</span>
            Tambah Murid
        </a>
    </div>
</div>

<!-- Card Utama Data Murid -->
<div class="activity-card" style="background: #ffffff; border-radius: 16px; padding: 24px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
    
    <!-- Filter Search & Total Murid -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div style="position: relative; display: flex; align-items: center; width: 320px;">
            <span class="material-symbols-outlined" style="position: absolute; left: 12px; color: #94a3b8; font-size: 20px;">search</span>
            <input type="text" placeholder="Cari nama atau NISN..." style="width: 100%; padding: 10px 14px 10px 40px; background: #f1f5f9; border: none; border-radius: 10px; font-size: 13px; outline: none; color: #334155;">
        </div>

        <div style="font-size: 13px; color: #64748b; font-weight: 500;">
            Total: <span style="background-color: #e0e7ff; color: #3730a3; padding: 4px 12px; border-radius: 12px; font-weight: 700; margin-left: 6px;">{{ count($siswas ?? [1,2,3,4,5]) }} Siswa</span>
        </div>
    </div>

    <!-- Tabel Data Murid -->
    <div class="table-wrapper">
        <table style="width: 100%; border-collapse: separate; border-spacing: 0;">
            <thead>
                <tr style="background-color: #f8fafc; color: #64748b; font-size: 11px; text-transform: uppercase; font-weight: 700;">
                    <th style="padding: 14px 16px; text-align: center; width: 60px; border-top-left-radius: 10px; border-bottom-left-radius: 10px;">ID</th>
                    <th style="padding: 14px 16px; text-align: left;">Nama Murid</th>
                    <th style="padding: 14px 16px; text-align: center;">NIS/NISN</th>
                    <th style="padding: 14px 16px; text-align: center;">Jenis Kelamin</th>
                    <th style="padding: 14px 16px; text-align: center; width: 100px; border-top-right-radius: 10px; border-bottom-right-radius: 10px;">Aksi</th>
                </tr>
            </thead>
            <tbody style="font-size: 13px;">
                @forelse($siswas ?? [] as $index => $siswa)
                <tr style="border-bottom: 1px solid #f1f5f9;">
                    <!-- ID urutan -->
                    <td style="padding: 16px; text-align: center; color: #64748b; font-weight: 500;">
                        {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                    </td>

                    <!-- Nama Murid + Avatar Inisial -->
                    <td style="padding: 16px;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 32px; height: 32px; background-color: #2b386b; color: #ffffff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 12px;">
                                {{ strtoupper(substr($siswa->nama ?? 'Nama', 0, 1)) }}
                            </div>
                            <span style="font-weight: 700; color: #1e293b;">{{ $siswa->nama ?? 'Siswa' }}</span>
                        </div>
                    </td>

                    <!-- NIS / NISN -->
                    <td style="padding: 16px; text-align: center; color: #64748b; font-weight: 500;">
                        {{ $siswa->nis ?? '10123' }} / {{ $siswa->nisn ?? '0061234567' }}
                    </td>

                    <!-- Jenis Kelamin -->
                    <td style="padding: 16px; text-align: center;">
                        @if(($siswa->jenis_kelamin ?? 'L') == 'L' || ($siswa->jenis_kelamin ?? '') == 'Laki-laki')
                            <span style="display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; background-color: #e0e7ff; color: #3730a3; border-radius: 50%;">
                                <span class="material-symbols-outlined" style="font-size: 16px;">male</span>
                            </span>
                        @else
                            <span style="display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; background-color: #fce7f3; color: #9d174d; border-radius: 50%;">
                                <span class="material-symbols-outlined" style="font-size: 16px;">female</span>
                            </span>
                        @endif
                    </td>

                    <!-- Aksi (Edit & Hapus) -->
                    <td style="padding: 16px; text-align: center;">
                        <div style="display: flex; justify-content: center; gap: 8px;">
                            <a href="{{ route('admin.siswa.edit', $siswa->id ?? 1) }}" style="color: #64748b; text-decoration: none; padding: 4px;">
                                <span class="material-symbols-outlined" style="font-size: 18px;">edit</span>
                            </a>
                            <form action="{{ route('admin.siswa.destroy', $siswa->id ?? 1) }}" method="POST" onsubmit="return confirm('Hapus siswa ini?')" style="display: inline;">
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
                        Belum ada siswa di kelas ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection