<?php

namespace App\Http\Controllers\Kesiswaan;

use App\Http\Controllers\Controller;
use App\Models\Dispen;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        if ($user->role !== 'Kesiswaan') {
            abort(403, 'Akses ditolak.');
        }

        $dispensMenunggu = Dispen::where('status', 'menunggu')->count();
        $notifikasi = $user->unreadNotifications()->latest()->take(10)->get();

        return view('kesiswaan.dashboard', compact('user', 'dispensMenunggu', 'notifikasi'));
    }
}
