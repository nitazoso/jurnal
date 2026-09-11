<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Guru;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('guru')->latest()->get();

        return view('admin.user.user', compact('users'));
    }

    public function create()
    {
        $gurus = Guru::orderBy('nama_guru')->get();

        return view('admin.user.add-user', compact('gurus'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:50|unique:users,username',
            'nama_user' => 'required|string|max:100',
            'password' => 'required|string|min:8',
            'role' => 'required|in:Admin,Guru,Sekretaris,Staff Piket',
            'id_guru' => 'nullable|exists:gurus,id_guru',
        ]);

        User::create($validated);

        return redirect()
            ->route('admin.user.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $gurus = Guru::orderBy('nama_guru')->get();

        return view('admin.user.edit-user', compact('user', 'gurus'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:50|unique:users,username,' . $user->id_user . ',id_user',
            'nama_user' => 'required|string|max:100',
            'role' => 'required|in:Admin,Guru,Sekretaris,Staff Piket',
            'id_guru' => 'nullable|exists:gurus,id_guru',
            'password' => 'nullable|string|min:8',
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        }   

        $user->update($validated);

        return redirect()
            ->route('admin.user.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()
            ->route('admin.user.index')
            ->with('success', 'User berhasil dihapus.');
    }
}