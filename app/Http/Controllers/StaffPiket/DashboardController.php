<?php

namespace App\Http\Controllers\StaffPiket;

use App\Http\Controllers\Controller;
use App\Models\Jurnal; // Sesuaikan dengan nama model Jurnal kamu
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Mengambil data jurnal (misal jurnal hari ini / terbaru)
        $jurnal = Jurnal::all(); // atau Jurnal::latest()->get();

        return view('staffpiket.dashboard', compact('jurnal'));
    }
}