@extends('layouts.piket')

@section('content')

<div class="p-6">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Data Dispen
            </h1>
            <p class="text-gray-500 mt-1">
                Kelola data dispensasi siswa
            </p>
        </div>

        <a href="{{ route('piket.dispen.create') }}"
           class="bg-[#30366f] text-white px-4 py-2 rounded-lg hover:opacity-90">
            + Tambah Dispen
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-3 rounded-lg bg-green-100 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow overflow-hidden">

        <table class="w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-3 text-left">No</th>
                    <th class="px-4 py-3 text-left">Siswa</th>
                    <th class="px-4 py-3 text-left">Tanggal</th>
                    <th class="px-4 py-3 text-left">Jam Mulai</th>
                    <th class="px-4 py-3 text-left">Jam Selesai</th>
                    <th class="px-4 py-3 text-left">Alasan</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-left">Dikonfirmasi oleh</th>
                    <th class="px-4 py-3 text-left">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($dispens as $dispen)
                    <tr class="border-t">
                        <td class="px-4 py-3">
                            {{ $dispens->firstItem() + $loop->index }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $dispen->siswa->nama_siswa ?? '-' }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $dispen->tanggal->format('d-m-Y') }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $dispen->jamMulai->jam_mulai ?? '-' }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $dispen->jamSelesai->jam_selesai ?? '-' }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $dispen->alasan }}
                        </td>
                        <td class="px-4 py-3">
                            @php
                                $statusClass = match($dispen->status) {
                                    'disetujui' => 'bg-green-100 text-green-700',
                                    'ditolak' => 'bg-red-100 text-red-700',
                                    default => 'bg-yellow-100 text-yellow-700',
                                };
                            @endphp
                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                                {{ $dispen->status === 'disetujui' ? 'Sudah terkonfirmasi' : ucfirst($dispen->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            @if ($dispen->approver)
                                <div class="font-medium">{{ $dispen->approver->nama_user }}</div>
                                @if ($dispen->disetujui_pada)
                                    <div class="text-xs text-gray-500">{{ $dispen->disetujui_pada->format('d-m-Y H:i') }}</div>
                                @endif
                            @else
                                <span class="text-gray-400">Belum dikonfirmasi</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
         <div class="flex gap-2">

        <a href="{{ route('piket.dispen.whatsapp', $dispen->id_dispen) }}"
           class="px-3 py-1 rounded-lg bg-green-600 text-white"
           target="_blank">
            Kirim ke Kesiswaan
        </a>

        <a href="{{ route('piket.dispen.edit', $dispen->id_dispen) }}"
           class="px-3 py-1 rounded-lg bg-yellow-500 text-white">
            Edit
        </a>

        <form action="{{ route('piket.dispen.destroy', $dispen->id_dispen) }}"
              method="POST"
              onsubmit="return confirm('Yakin ingin menghapus data dispen ini?')">

            @csrf
            @method('DELETE')

            <button type="submit"
                    class="px-3 py-1 rounded-lg bg-red-600 text-white">
                Hapus
            </button>

        </form>

    </div>
</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-4 py-8 text-center text-gray-500">
                            Belum ada data dispen.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>

    <div class="mt-4">
        {{ $dispens->links() }}
    </div>

</div>

@endsection
