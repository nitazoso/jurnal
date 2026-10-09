@extends('layouts.piket')

@section('title', 'Riwayat Dispen - Jurnify')
@section('page-title', 'Riwayat Dispen')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Riwayat Dispen</h1>
        <p class="mt-1 text-gray-500">Daftar dispen yang sudah dikonfirmasi Kesiswaan.</p>
    </div>

    <div class="overflow-x-auto rounded-xl bg-white shadow">
        <table class="w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-3 text-left">No</th>
                    <th class="px-4 py-3 text-left">Siswa</th>
                    <th class="px-4 py-3 text-left">Tanggal</th>
                    <th class="px-4 py-3 text-left">Jam</th>
                    <th class="px-4 py-3 text-left">Alasan</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-left">Dikonfirmasi oleh</th>
                    <th class="px-4 py-3 text-left">Catatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($dispens as $dispen)
                    @php
                        $statusClass = $dispen->status === 'disetujui'
                            ? 'bg-green-100 text-green-700'
                            : 'bg-red-100 text-red-700';
                    @endphp
                    <tr class="border-t">
                        <td class="px-4 py-3">{{ $dispens->firstItem() + $loop->index }}</td>
                        <td class="px-4 py-3">{{ $dispen->siswa->nama_siswa ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $dispen->tanggal?->format('d-m-Y') ?? '-' }}</td>
                        <td class="px-4 py-3">
                            {{ $dispen->jamMulai->jam_mulai ?? '-' }} - {{ $dispen->jamSelesai->jam_selesai ?? '-' }}
                        </td>
                        <td class="px-4 py-3">{{ $dispen->alasan ?: '-' }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                                {{ ucfirst($dispen->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="font-medium">{{ $dispen->approver->nama_user ?? '-' }}</div>
                            <div class="text-xs text-gray-500">{{ $dispen->disetujui_pada?->format('d-m-Y H:i') ?? '-' }}</div>
                        </td>
                        <td class="px-4 py-3">{{ $dispen->catatan_persetujuan ?: '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center text-gray-500">Belum ada riwayat dispen.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $dispens->links() }}</div>
</div>
@endsection