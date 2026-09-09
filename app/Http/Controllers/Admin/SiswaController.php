<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Siswa;

class SiswaController extends Controller
{
    public function index(Request $request)
{
    $query = Siswa::query();

    if ($request->has('kelas_id')) {
        $query->where('id_kelas', $request->kelas_id);
    }

    $siswas = $query->paginate(10);

    return view('admin.kelas.siswa', compact('siswas'));
}
}
