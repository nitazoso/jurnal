<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Jurnal;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $totalJurnalHariIni = Jurnal::whereDate('created_at', now()->toDateString())->count();
        $totalGuru = Guru::count();
        $totalKelas = Kelas::count();
        
        // Ambil data semua kelas untuk dropdown filter
        $kelases = Kelas::all();

        $jurnals = Jurnal::with(['guru', 'kelas', 'jadwal.mapel']) // Load relasi sampai ke mapel
            ->when($request->search, function ($query, $search) {
            $query->where('materi', 'like', "%{$search}%")
              
              ->orWhereHas('jadwal.mapel', function ($q) use ($search) {
                  $q->where('nama_mapel', 'like', "%{$search}%"); 
              })
              ->orWhereHas('guru', function ($q) use ($search) {
                  $q->where('nama', 'like', "%{$search}%");
              });
             })
             ->when($request->status, function ($query, $status) {
                 $query->where('status_validasi_guru', $status); 
             })
             ->when($request->kelas_id, function ($query, $kelasId) {
                 $query->where('id_kelas', $kelasId);
             })
             ->when($request->tanggal, function ($query, $tanggal) {
                 $query->whereDate('tanggal', $tanggal);
             })
        ->latest()
        ->paginate(10)
        ->withQueryString();
        return view('admin.dashboard', compact(
            'totalJurnalHariIni', 
            'totalGuru', 
            'totalKelas', 
            'kelases',
            'jurnals'
        ));
    }
}