@extends('layouts.admin')

@section('title', 'Manajemen Kelas')

@section('content')
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
    }
</style>

<div class="header-action-wrapper" style="display: flex; justify-content: flex-end; align-items: center; margin-bottom: 20px;">
    <a href="{{ route('admin.kelas.create') }}" class="btn-primary" style="background-color: #1B234A; color: #fff; padding: 10px 18px; border-radius: 10px; text-decoration: none; font-weight: 600; font-size: 13px; display: inline-flex; align-items: center; gap: 8px;">
        <span class="material-symbols-outlined" style="font-size: 18px;">person_add</span>
        Tambah Kelas
    </a>
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
            
            <button class="filter-btn" style="background: #f1f5f9; border: none; padding: 8px 12px; border-radius: 10px; cursor: pointer; display: flex; align-items: center;">
                <span class="material-symbols-outlined" style="font-size: 18px; color: #64748b;">tune</span>
            </button>
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
                       <a href="{{ route('admin.siswa.index', ['kelas_id' => $item->id_kelas ?? $item->id]) }}" class="action-btn" style="color: #64748b; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; background: #f8fafc; border-radius: 50%; transition: all 0.2s;">
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
@endsection