<?php

namespace Database\Seeders;

use App\Models\Mapel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MapelSeeder extends Seeder
{
    public function run(): void
    {
        $namaMapels = [
            'Matematika',
            'Bahasa Inggris',
            'Bahasa Jawa',
            'Bahasa Jepang',
            'PJOK',
            'Seni Budaya',
            'IPAS',
            'Informatika',
            'BK',
            'Pendidikan Agama Islam dan Budi Pekerti',
            'Koding dan Kecerdasan Artifisial',
            'Dasar AKL',
            'Dasar AN',
            'Dasar BP',
            'Dasar DKV',
            'Dasar MPLB',
            'Dasar PM',
            'Dasar PPLG',
            'Dasar TJKT',
            'Dasar TKI',
            'Dasar ULP',
            'Konsentrasi AK',
            'Konsentrasi AN',
            'Konsentrasi BD',
            'Konsentrasi DKV',
            'Konsentrasi MP',
            'Konsentrasi PSPT',
            'Konsentrasi RPL',
            'Konsentrasi TKI',
            'Konsentrasi TKJ',
            'Konsentrasi ULW',
            'Kreativitas, Inovasi, dan Kewirausahaan',
        ];

        DB::transaction(function () use ($namaMapels): void {
            foreach ($namaMapels as $namaMapel) {
                $mapel = Mapel::withTrashed()
                    ->whereRaw('LOWER(nama_mapel) = ?', [mb_strtolower($namaMapel)])
                    ->first();

                if ($mapel) {
                    if ($mapel->trashed()) {
                        $mapel->restore();
                    }

                    continue;
                }

                Mapel::create(['nama_mapel' => $namaMapel]);
            }
        });
    }
}
