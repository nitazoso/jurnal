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

@endsection