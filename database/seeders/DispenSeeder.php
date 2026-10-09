<?php

namespace Database\Seeders;

use App\Models\Dispen;
use App\Models\Guru;
use App\Models\JamPel;
use App\Models\PiketJadwal;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DispenSeeder extends Seeder
{
    public function run(): void
    {
        $siswa = Siswa::orderBy('id_siswa')->take(3)->get()->values();
        $jam = JamPel::where('jenis', 'pelajaran')->orderBy('jam_ke')->take(4)->get()->values();

        if ($siswa->count() < 3 || $jam->isEmpty()) {
            return;
        }

        $wakaDuty = PiketJadwal::with('guru')
            ->where('jenis_tugas', 'Piket Waka')
            ->orderBy('tanggal')
            ->first();

        // Pastikan database demo memiliki minimal satu penugasan Waka yang nyata.
        if (! $wakaDuty) {
            $wakaUser = User::whereNotNull('id_guru')->orderBy('username')->first();
            if (! $wakaUser) {
                return;
            }

            PiketJadwal::create([
                'id_guru' => $wakaUser->id_guru,
                'tanggal' => today('Asia/Jakarta')->toDateString(),
                'shift' => 'Waka',
                'jenis_tugas' => 'Piket Waka',
                'posisi' => 'Petugas',
            ]);

            $wakaDuty = PiketJadwal::with('guru')
                ->where('jenis_tugas', 'Piket Waka')
                ->whereDate('tanggal', today('Asia/Jakarta')->toDateString())
                ->first();
        }

        $guru = $wakaDuty?->guru ?? Guru::find($wakaDuty?->id_guru);
        if (! $guru) {
            return;
        }

        $waka = User::where('id_guru', $guru->id_guru)->first();
        if (! $waka) {
            $baseUsername = 'waka_' . Str::slug($guru->nama_guru, '_');
            $username = $baseUsername;
            $suffix = 1;
            while (User::where('username', $username)->exists()) {
                $username = $baseUsername . '_' . $suffix++;
            }

            $waka = User::create([
                'username' => $username,
                'password' => 'password123',
                'nama_user' => $guru->nama_guru,
                'role' => 'Guru',
                'id_guru' => $guru->id_guru,
                'no_wa' => '6280000000000',
            ]);
        } elseif (! $waka->no_wa) {
            $waka->update(['no_wa' => '6280000000000']);
        }

        $tanggal = $wakaDuty->tanggal->toDateString();
        $piket = User::where('username', 'wildan_piket')->first();
        $samples = [
            [
                'siswa' => $siswa[0], 'status' => 'menunggu', 'alasan' => 'Mengikuti kegiatan seleksi olimpiade sains tingkat kabupaten',
                'jam_mulai' => $jam[0], 'jam_selesai' => $jam[min(1, $jam->count() - 1)],
                'decision_by' => null, 'decided_at' => null, 'reason' => null,
            ],
            [
                'siswa' => $siswa[1], 'status' => 'disetujui', 'alasan' => 'Menjadi delegasi seminar kepemimpinan OSIS tingkat kota',
                'jam_mulai' => $jam[0], 'jam_selesai' => $jam[min(2, $jam->count() - 1)],
                'decision_by' => $waka->id_user, 'decided_at' => now()->subDays(2), 'reason' => null,
            ],
            [
                'siswa' => $siswa[2], 'status' => 'ditolak', 'alasan' => 'Keperluan urusan keluarga di luar kota',
                'jam_mulai' => $jam[min(1, $jam->count() - 1)], 'jam_selesai' => $jam[min(3, $jam->count() - 1)],
                'decision_by' => $waka->id_user, 'decided_at' => now()->subDay(),
                'reason' => 'Pengajuan tidak memenuhi kriteria dispensasi akademik.',
            ],
        ];

        foreach ($samples as $sample) {
            $existing = Dispen::where('id_siswa', $sample['siswa']->id_siswa)
                ->where('tanggal', $tanggal)
                ->where('jenis', 'dispen')
                ->first();

            Dispen::updateOrCreate(
                [
                    'id_siswa' => $sample['siswa']->id_siswa,
                    'tanggal' => $tanggal,
                    'jenis' => 'dispen',
                ],
                [
                    'id_kesiswaan' => $waka->id_user,
                    'submitted_by' => $piket?->id_user,
                    'id_jam_mulai' => $sample['jam_mulai']->id_jam,
                    'id_jam_selesai' => $sample['jam_selesai']->id_jam,
                    'alasan' => $sample['alasan'],
                    'status' => $sample['status'],
                    'token_verifikasi' => $existing?->token_verifikasi ?: Str::random(64),
                    'disetujui_oleh' => $sample['decision_by'],
                    'disetujui_pada' => $sample['decided_at'],
                    'catatan_persetujuan' => $sample['reason'],
                ]
            );
        }
    }
}
