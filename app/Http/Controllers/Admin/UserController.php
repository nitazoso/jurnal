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
                    ->orWhere('nama_user', 'like', '%' . $search . '%')
                    ->orWhereHas('guru', function ($guruQuery) use ($search) {
                        $guruQuery->where('nama_guru', 'like', '%' . $search . '%');
                    });
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

        // =========================
        // STATISTIK
        // =========================
        $totalUser = User::count();

        // TAMBAHAN: Total Admin
        $totalAdmin = User::where('role', 'Admin')->count();

        $totalGuru = User::where('role', 'Guru')->count();

        $totalStaffPiket = User::where('role', 'Staff Piket')->count();

        $totalSekretaris = User::where('role', 'Sekretaris')->count();

        $totalSiswa = \App\Models\Siswa::count();

        return view('admin.user.index', compact(
            'users',
            'totalUser',
            'totalAdmin',
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
            'nama_user' => 'nullable|string|max:255',
            'password' => 'required|string|min:8',
            'role' => 'required|in:Admin,Guru,Staff Piket,Sekretaris',
            'id_guru' => 'nullable|exists:gurus,id_guru',
            'id_kelas' => 'nullable|exists:kelases,id_kelas',
        ]);

        $plainPassword = $validated['password'];

        $validated['nama_user'] = !empty($validated['id_guru'])
            ? Guru::findOrFail($validated['id_guru'])->nama_guru
            : ($validated['nama_user'] ?? $validated['username']);

        $validated['password'] = Hash::make($plainPassword);

        $user = User::create($validated);

        return redirect()
            ->route('admin.user.receipt', $user->id_user)
            ->with([
                'password_awal' => $plainPassword,
                'created_by' => auth()->user()->nama_user ?? 'Administrator',
            ]);
    }

    // =========================
    // RECEIPT
    // =========================
    public function receipt(User $user)
    {
        $passwordAwal = session('password_awal');

        if (!$passwordAwal) {
            return redirect()
                ->route('admin.user.index')
                ->with('error', 'Struk akun tidak tersedia.');
        }

        return view('admin.user.receipt', [
            'user' => $user,
            'passwordAwal' => $passwordAwal,
            'createdBy' => session(
                'created_by',
                auth()->user()->nama_user ?? 'Administrator'
            ),
            'tanggalDibuat' => now()->translatedFormat('d F Y'),
        ]);
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
            'nama_user' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:8',
            'role' => 'required|in:Admin,Guru,Staff Piket,Sekretaris',
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

        if (!empty($validated['id_guru'])) {
            $validated['nama_user'] = Guru::findOrFail($validated['id_guru'])->nama_guru;
        } elseif (empty($validated['nama_user'])) {
            unset($validated['nama_user']);
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