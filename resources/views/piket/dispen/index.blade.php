@extends('layouts.piket')

@section('title', 'Data Dispen - Jurnify')
@section('page-title', 'Data Dispen')

@section('content')

<div class="p-6">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Data Dispen
            </h1>
            <p class="text-gray-500 mt-1">
                Kelola data dispensasi siswa dan teruskan permohonan ke Waka/Kesiswaan bertugas via WhatsApp.
            </p>
        </div>

        <a href="{{ route('piket.dispen.create') }}"
           class="bg-[#30366f] text-white px-4 py-2 rounded-lg hover:opacity-90 flex items-center gap-2">
            <span>+ Tambah Dispen</span>
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-3.5 rounded-lg bg-green-100 border border-green-200 text-green-700 flex items-center gap-2">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-3.5 rounded-lg bg-red-100 border border-red-200 text-red-700 flex items-center gap-2">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow overflow-hidden">

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-100 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-3 text-left">No</th>
                        <th class="px-4 py-3 text-left">Siswa</th>
                        <th class="px-4 py-3 text-left">Kelas</th>
                        <th class="px-4 py-3 text-left">Tanggal</th>
                        <th class="px-4 py-3 text-left">Jam</th>
                        <th class="px-4 py-3 text-left">Alasan</th>
                        <th class="px-4 py-3 text-left">Waka Bertugas</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                    @forelse($dispens as $dispen)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 text-gray-500">
                                {{ $dispens->firstItem() + $loop->index }}
                            </td>

                            <td class="px-4 py-3 font-semibold text-gray-800">
                                {{ $dispen->siswa->nama_siswa ?? '-' }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $dispen->siswa->kelas->nama_kelas ?? '-' }}
                            </td>

                            <td class="px-4 py-3 font-medium">
                                {{ $dispen->tanggal ? $dispen->tanggal->format('d-m-Y') : '-' }}
                            </td>

                            <td class="px-4 py-3 whitespace-nowrap text-xs">
                                Jam ke-{{ $dispen->jamMulai->jam_ke ?? '?' }} - ke-{{ $dispen->jamSelesai->jam_ke ?? '?' }}
                                <br>
                                <span class="text-gray-500">
                                    ({{ substr($dispen->jamMulai->jam_mulai ?? '', 0, 5) }} - {{ substr($dispen->jamSelesai->jam_selesai ?? '', 0, 5) }})
                                </span>
                            </td>

                            <td class="px-4 py-3 max-w-xs truncate" title="{{ $dispen->alasan }}">
                                {{ $dispen->alasan }}
                            </td>

                            <td class="px-4 py-3">
                                @if ($dispen->petugasKesiswaan)
                                    <div class="font-medium text-gray-900">{{ $dispen->petugasKesiswaan->nama_user }}</div>
                                    @if ($dispen->petugasKesiswaan->no_wa)
                                        <div class="text-xs text-green-600 font-mono">{{ $dispen->petugasKesiswaan->no_wa }}</div>
                                    @else
                                        <div class="text-xs text-amber-500 italic">No WA belum ada</div>
                                    @endif
                                @else
                                    <span class="text-xs text-gray-400 italic">Sesuai jadwal</span>
                                @endif
                            </td>

                            <td class="px-4 py-3">
                                @php
                                    $statusClass = match($dispen->status) {
                                        'disetujui' => 'bg-green-100 text-green-700 border-green-200',
                                        'ditolak' => 'bg-red-100 text-red-700 border-red-200',
                                        default => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                    };
                                @endphp
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold border {{ $statusClass }}">
                                    {{ ucfirst($dispen->status) }}
                                </span>
                            </td>

                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('piket.dispen.whatsapp', $dispen->id_dispen) }}"
                                       class="px-3 py-1.5 rounded-lg bg-green-600 hover:bg-green-700 text-white font-medium text-xs inline-flex items-center gap-1 shadow-sm transition"
                                       target="_blank"
                                       title="Kirim ke WhatsApp Waka/Kesiswaan bertugas">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.669-.699c.969.529 1.771.814 2.791.814 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.768-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.007c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.173.086.275.072.376-.044.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.099.824zM12 2C6.477 2 2 6.477 2 12c0 1.891.524 3.66 1.434 5.176L2 22l4.957-1.396A9.957 9.957 0 0 0 12 22c5.523 0 10-4.477 10-10S17.523 2 12 2z"/></svg>
                                        <span>Kirim WhatsApp</span>
                                    </a>

                                    <a href="{{ route('piket.dispen.edit', $dispen->id_dispen) }}"
                                       class="px-2.5 py-1.5 rounded-lg bg-yellow-500 hover:bg-yellow-600 text-white font-medium text-xs transition">
                                        Edit
                                    </a>

                                    <form action="{{ route('piket.dispen.destroy', $dispen->id_dispen) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus data dispen ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="px-2.5 py-1.5 rounded-lg bg-red-600 hover:bg-red-700 text-white font-medium text-xs transition">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-8 text-center text-gray-500">
                                Belum ada data dispen yang berstatus menunggu.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

    <div class="mt-4">
        {{ $dispens->links() }}
    </div>

</div>

@endsection
