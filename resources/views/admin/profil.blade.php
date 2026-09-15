@extends('layouts.admin')

@section('title', 'Profil - Jurnify')

@section('page-title', 'Profil')

@section('content')

<div class="activity-header">
    <h3 class="activity-title">
        Profil Admin
    </h3>

    <p class="activity-description">
        Informasi akun yang sedang digunakan.
    </p>
</div>

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

<div style="margin-top: 24px;">
    <form action="{{ route('logout') }}" method="POST" onsubmit="return confirm('Anda yakin ingin logout?');">
        @csrf
        <button type="submit" style="
            background: #dc2626;
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