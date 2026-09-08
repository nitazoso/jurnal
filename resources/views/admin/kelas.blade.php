@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header font-weight-bold">Data Kelas</div>

                    <div class="card-body">

                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <a href="{{ route('admin.kelas.create') }}" class="btn btn-primary mb-3">
                            + Tambah Kelas
                        </a>

                        <table class="table table-bordered align-middle">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Kelas</th>
                                    <th>Wali Kelas</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($kelas as $k)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>

                                        <td>
                                            {{ $k->nama_kelas }}
                                        </td>

                                        <td>{{ $k->waliKelas->nama_guru ?? '-' }}</td>

                                        <td>
                                            <a href="{{ route('admin.kelas.edit', ['kelas' => $k->id_kelas]) }}"
                                               class="btn btn-warning btn-sm">
                                                Edit
                                            </a>

                                            <form action="{{ route('admin.kelas.destroy', ['kelas' => $k->id_kelas]) }}"
                                                  method="POST"
                                                  style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Apakah Anda yakin ingin menghapus kelas ini?')">
                                                    Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>

                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">
                                            Data kelas belum ada.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection