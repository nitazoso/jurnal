<?php

namespace App\Exports;

use App\Models\Jadwal;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class JadwalExport implements FromCollection, ShouldAutoSize, WithHeadings
{
    public function __construct(private readonly ?int $kelasId = null)
    {
    }

    public function collection(): Collection
    {
        return Jadwal::query()
            ->with(['guru', 'mapel', 'kelas', 'jamMulai', 'jamSelesai'])
            ->when($this->kelasId, fn ($query) => $query->where('id_kelas', $this->kelasId))
            ->orderBy('hari')
            ->orderBy('id_kelas')
            ->orderBy('id_jam_mulai')
            ->get()
            ->map(fn (Jadwal $jadwal) => [
                'kelas' => $jadwal->kelas?->nama_kelas ?? '-',
                'hari' => $jadwal->hari,
                'jam_mulai' => $jadwal->jamMulai?->jam_ke ?? '-',
                'jam_selesai' => $jadwal->jamSelesai?->jam_ke ?? '-',
                'waktu' => trim(($jadwal->jamMulai?->jam_mulai ?? '') . ' - ' . ($jadwal->jamSelesai?->jam_selesai ?? '')),
                'mapel' => $jadwal->mapel?->nama_mapel ?? '-',
                'guru' => $jadwal->guru?->nama_guru ?? '-',
                'semester' => $jadwal->semester,
                'tahun_ajaran' => $jadwal->tahun_ajaran,
            ]);
    }

    public function headings(): array
    {
        return [
            'Kelas',
            'Hari',
            'Jam Pelajaran Mulai',
            'Jam Pelajaran Selesai',
            'Waktu',
            'Mata Pelajaran',
            'Guru',
            'Semester',
            'Tahun Ajaran',
        ];
    }
}
