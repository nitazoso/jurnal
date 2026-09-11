<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Guru;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // =========================
    // INDEX
    // =========================
    public function index(Request $request)
    {
        $query = User::with(['guru', 'kelas']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('username', 'like', '%' . $search . '%')
                ->orWhere('nama_user', 'like', '%' . $search . '%');
            });
        }

        // Filter role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query
            ->latest('id_user')
            ->paginate(10)
            ->withQueryString();

        // Statistik
        $totalUser = User::count();
        $totalGuru = User::where('role', 'Guru')->count();
        $totalStaffPiket = User::where('role', 'Staff Piket')->count();
        $totalSekretaris = User::where('role', 'Sekretaris')->count();
        $totalSiswa = \App\Models\Siswa::count();

        return view('admin.user.index', compact(
            'users',
            'totalUser',
            'totalGuru',
            'totalStaffPiket',
            'totalSekretaris',
            'totalSiswa'
        ));
    }

    // =========================
    // CREATE
    // =========================
    public function create()
    {
        $gurus = Guru::orderBy('nama_guru')->get();
        $kelases = Kelas::orderBy('nama_kelas')->get();

        return view('admin.user.create', compact(
            'gurus',
            'kelases'
        ));
    }

    // =========================
    // STORE
    // =========================
    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:100|unique:users,username',
            'nama_user' => 'required|string|max:255',
            'password' => 'required|string|min:6',
            'role' => 'required|in:Admin,Guru,Sekretaris,Staff Piket',
            'id_guru' => 'nullable|exists:gurus,id_guru',
            'id_kelas' => 'nullable|exists:kelases,id_kelas',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()
            ->route('admin.user.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    // =========================
    // EDIT
    // =========================
    public function edit($id)
    {
        $user = User::findOrFail($id);

        $gurus = Guru::orderBy('nama_guru')->get();
        $kelases = Kelas::orderBy('nama_kelas')->get();

        return view('admin.user.edit', compact(
            'user',
            'gurus',
            'kelases'
        ));
    }

    // =========================
    // UPDATE
    // =========================
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'username' => 'required|string|max:100|unique:users,username,' . $user->id_user . ',id_user',
            'nama_user' => 'required|string|max:255',
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:Admin,Guru,Sekretaris,Staff Piket',
            'id_guru' => 'nullable|exists:gurus,id_guru',
            'id_kelas' => 'nullable|exists:kelases,id_kelas',
        ]);

        // Kalau password diisi, hash password baru
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            // Kalau kosong, password lama tetap digunakan
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()
            ->route('admin.user.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    // =========================
    // DELETE
    // =========================
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return redirect()
            ->route('admin.user.index')
            ->with('success', 'User berhasil dihapus.');
    }
}