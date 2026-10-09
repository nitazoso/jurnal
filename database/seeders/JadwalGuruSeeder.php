<?php

namespace Database\Seeders;

use App\Models\Guru;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JadwalGuruSeeder extends Seeder
{
    /** Names present in the uploaded 2026/2027 timetable but missing from gurus. */
    private const GURU_JADWAL = [
        "Muto'atul Khosi'ah, S.Pd",
        'Ilham Sungeidi, S.Pd',
        'Yani, S.Pd.',
        'Yustin Febrini, S.Pd',
        'Anang Prasetyo, S.Pd',
        'Endang Ary Handayani, S.T., M.Pd',
        'Agus Muharyanto, M.Pd',
        'Muashofah, M.Pd',
        'Ista Nofasari, S.Pd',
        'Abdul Rohman, S.Pd',
        'Indriati, S.Pd',
        'Bella Prakoso, S.Pd',
        'Eko Saputro, S.Pd',
        'Khoyrotun Hisani, S.Sn',
        'Isti Mufadah, S.Pd',
        'Sri Subekti, S.Pd',
        'Endang Safitri, S.Pd',
        'Andri Retno Yuli Astuti, S.Pd',
        'Ajeng Okvitasari, S.Pd',
        'Fitria Diah Ayu Hartati, S.Pd',
        'Rulik Indrawati, S.Pd',
        'Alfinu Farikh Abdillah, S.Pd.I',
        'Ary Sunaryo, S.T., M.Pd',
        'Agus Fahruddy, S.Pd., M.Pd',
        'Agus Pramono, S.Sn',
        'Joko Priyanto, S.Kom',
        'Indayah, S.Pd., M.Pd',
        'Benny Mamora, S.Kom',
        'Septiani, S.Pd., M.Pd',
        'Danang Anjar Hymawanto, S.Pd',
        'Nur Nastutisari, S.ST.Par.',
        "Sa'ad Wazis Hiedayat, S.Pd",
        'Andika Christian Sasmita, S.ST',
        'Retno Widyastuti, S.Pd., M.Pd',
        'Rindang Rejeki, S.Pd',
        'Yuli Ratnasari, S.Pd',
        'Titin Sukmasari, S.Pd., M.Pd',
        'Ayu Puspitorini, S.T',
        'Agung Yulianto, S.Pd',
        'Rulitasari, S.Pd',
        'Astra Bella Flamboyan, S.Psi',
        'Muhammad Fajar Assidiqi, S.Pd',
        'Rizki Putri Wulandari, S.Pd',
        'Istiana Suhartati, S.T',
        'Siti Umiharsih, S.Pd',
        'Dyah Esti Rahayu, S.Pd',
        'Agustina Mardika Rini, S.Pd., M.Pd',
        'Dian Mawarti, S.Pd',
        'Listyana Hartati, S.Kom., M.Pd',
        'Erwan Septiyono, S.Pd',
    ];

    public function run(): void
    {
        DB::transaction(function (): void {
            foreach (self::GURU_JADWAL as $namaGuru) {
                $guru = Guru::withTrashed()
                    ->whereRaw('LOWER(nama_guru) = ?', [mb_strtolower($namaGuru)])
                    ->first();

                if ($guru) {
                    if ($guru->trashed()) {
                        $guru->restore();
                    }

                    continue;
                }

                Guru::create(['nama_guru' => $namaGuru]);
            }
        });
    }
}
