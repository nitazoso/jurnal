<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\DetailAbsensi;
use App\Models\Jadwal;
use App\Models\Jurnal;
use App\Models\Siswa;
use Illuminate\Http\Request;

class JurnalController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $jurnals = Jurnal::with([
            'guru',
            'kelas',
            'jadwal.mapel',
            'jamMulai',
            'jamSelesai',
        ])
        // Jurnal dapat diisi langsung oleh guru atau dibantu sekretaris.
        // Kepemilikan jurnal pembelajaran tetap mengikuti guru pengampu.
        ->where('id_guru', $user->id_guru)
        ->latest('tanggal')
        ->get();

        return view('guru.jurnal.index', compact('jurnals'));
    }

    public function create()
    {
        $user = auth()->user();

        $jadwals = Jadwal::with([
            'kelas',
            'mapel',
            'jamMulai',
            'jamSelesai',
        ])
        ->where('id_guru', $user->id_guru)
        ->orderBy('hari')
        ->orderBy('id_jam_mulai')
        ->get();

        return view('guru.jurnal.create', compact('jadwals'));
    }

    public function form(Jadwal $jadwal)
    {
        $user = auth()->user();

        // Pastikan jadwal memang milik guru yang sedang login
        if ($jadwal->id_guru != $user->id_guru) {
            abort(403);
        }

        // Ambil data relasi jadwal
        $jadwal->load([
            'kelas',
            'mapel',
            'jamMulai',
            'jamSelesai',
        ]);

        // Ambil semua siswa dari kelas jadwal tersebut
        $siswa = Siswa::where('id_kelas', $jadwal->id_kelas)
            ->orderBy('nama_siswa')
            ->get();

        return view('guru.jurnal.form', compact('jadwal', 'siswa'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_jadwal' => 'required|exists:jadwals,id_jadwal',
            'tanggal' => 'required|date',
            'materi' => 'required|string|max:255',

            'status_guru' => [
                'required',
                'in:Hadir,Izin,Sakit,Tanpa Keterangan',
            ],

            'ada_tugas' => [
                'required',
                'in:Ya,Tidak',
            ],

            'deskripsi_tugas' => 'nullable|string',
            'catatan_umum' => 'nullable|string|max:255',

            'absensi' => 'required|array',
            'absensi.*' => [
                'required',
                'in:Hadir,Sakit,Izin,Alpha,Dispen',
            ],
        ]);

        $user = auth()->user();

        // Ambil jadwal
        $jadwal = Jadwal::findOrFail($validated['id_jadwal']);

        // Pastikan jadwal milik guru yang login
        if ($jadwal->id_guru != $user->id_guru) {
            abort(403);
        }

        // Hitung jumlah hadir dan tidak hadir
        $jmlHadir = 0;
        $jmlTidakHadir = 0;

        foreach ($validated['absensi'] as $status) {
            if ($status === 'Hadir') {
                $jmlHadir++;
            } else {
                $jmlTidakHadir++;
            }
        }

        // Simpan jurnal
        $jurnal = Jurnal::create([
            'id_jadwal' => $jadwal->id_jadwal,
            'id_kelas' => $jadwal->id_kelas,
            'id_guru' => $jadwal->id_guru,
            'id_user' => $user->id_user,

            'id_jam_mulai' => $jadwal->id_jam_mulai,
            'id_jam_selesai' => $jadwal->id_jam_selesai,

            'tanggal' => $validated['tanggal'],
            'materi' => $validated['materi'],

            'status_guru' => $validated['status_guru'],

            'ada_tugas' => $validated['ada_tugas'],
            'deskripsi_tugas' => $validated['deskripsi_tugas'] ?? null,

            'jml_hadir' => $jmlHadir,
            'jml_tidak_hadir' => $jmlTidakHadir,

            'status_validasi_guru' => 'Menunggu',

            'catatan_umum' => $validated['catatan_umum'] ?? null,
        ]);

        // Simpan detail siswa yang tidak hadir
        foreach ($validated['absensi'] as $idSiswa => $status) {

            // Hadir tidak perlu disimpan
            // karena jumlah hadir sudah disimpan di jurnal
            if ($status === 'Hadir') {
                continue;
            }

            DetailAbsensi::create([
                'id_jurnal' => $jurnal->id_jurnal,
                'id_siswa' => $idSiswa,
                'status' => $status,
                'keterangan' => null,
            ]);
        }

        return redirect()
            ->route('guru.jurnal.index')
            ->with('success', 'Jurnal berhasil disimpan.');
    }

    public function show(Jurnal $jurnal)
    {
        $user = auth()->user();

        // Guru hanya boleh melihat jurnal miliknya
        if ($jurnal->id_guru != $user->id_guru) {
            abort(403);
        }

        $jurnal->load([
            'guru',
            'kelas',
            'jadwal.mapel',
            'jamMulai',
            'jamSelesai',
        ]);

        return view('guru.jurnal.show', compact('jurnal'));
    }
}
