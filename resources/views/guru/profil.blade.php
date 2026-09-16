@extends('layouts.guru')

@section('title', 'Profil Guru - Jurnify')

@section('content')

<div class="card">
    <div class="card-title">
        Profil Guru
    </div>

    <div class="card-subtitle">
        Informasi akun yang sedang digunakan.
    </div>

    <br>

    <div class="table-wrapper" style="max-width: 700px;">
        <table>
            <tbody>
                <tr>
                    <th style="width: 200px;">Nama</th>
                    <td>{{ auth()->user()->nama_user ?? '-' }}</td>
                </tr>

                <tr>
                    <th>Username</th>
                    <td>{{ auth()->user()->username ?? '-' }}</td>
                </tr>

                <tr>
                    <th>Role</th>
                    <td>{{ auth()->user()->role ?? '-' }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <br>

    <form action="{{ route('logout') }}" method="POST" style="margin-top: 16px;" onsubmit="return confirm('Anda yakin ingin logout?');">
        @csrf
        <button type="submit" style="
            background: #d32f2f;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 10px 18px;
            font-weight: 600;
            cursor: pointer;
        ">
            Logout
        </button>
    </form>
</div>

@endsection
