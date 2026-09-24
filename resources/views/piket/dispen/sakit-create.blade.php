@extends('layouts.piket')

@section('title', 'Kirim Surat Sakit - Jurnify')
@section('page-title', 'Kirim Surat Sakit')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Kirim Surat Sakit</h1>
        <p class="text-gray-500 mt-1">Data akan otomatis muncul sebagai Sakit pada jurnal guru.</p>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 rounded-lg bg-green-100 text-green-700">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="mb-4 p-4 rounded-lg bg-red-100 text-red-700">
            <ul class="list-disc pl-5">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow p-6">
        <form action="{{ route('piket.dispen.sakit.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label class="block mb-2 font-medium text-gray-700">Kelas</label>
                <select name="id_kelas" id="sick-class" class="w-full border border-gray-300 rounded-lg px-4 py-2" required>
                    <option value="">-- Pilih Kelas --</option>
                    @foreach($kelases as $kelas)
                        <option value="{{ $kelas->id_kelas }}">{{ $kelas->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="block mb-2 font-medium text-gray-700">Siswa</label>
                <select name="id_siswa" id="sick-student" class="w-full border border-gray-300 rounded-lg px-4 py-2" required disabled>
                    <option value="">-- Pilih kelas terlebih dahulu --</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block mb-2 font-medium text-gray-700">Tanggal</label>
                <input type="date" name="tanggal" value="{{ old('tanggal', today()->toDateString()) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2" required>
            </div>
            <div class="mb-4">
                <label class="block mb-2 font-medium text-gray-700">Keterangan Sakit</label>
                <textarea name="alasan" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2" required>{{ old('alasan') }}</textarea>
            </div>
            <div class="mb-6">
                <label class="block mb-2 font-medium text-gray-700">Foto Surat <span class="font-normal text-gray-400">(opsional)</span></label>
                <input type="file" name="surat" accept="image/*" class="w-full border border-gray-300 rounded-lg px-4 py-2">
                <p class="mt-1 text-sm text-gray-500">Format gambar, maksimal 5 MB.</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('piket.dispen.index') }}" class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700">Kembali</a>
                <button type="submit" class="px-4 py-2 rounded-lg bg-[#30366f] text-white">Kirim Surat Sakit</button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow p-6 mt-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Status Surat Sakit Hari Ini</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr class="text-left border-b"><th class="py-3 pr-4">Siswa</th><th class="py-3 pr-4">Kelas</th><th class="py-3 pr-4">Status</th><th class="py-3">Surat</th></tr></thead>
                <tbody>
                    @forelse($sickReports as $report)
                        <tr class="border-b last:border-0">
                            <td class="py-3 pr-4">{{ $report->siswa?->nama_siswa ?? '-' }}</td>
                            <td class="py-3 pr-4">{{ $report->siswa?->kelas?->nama_kelas ?? '-' }}</td>
                            <td class="py-3 pr-4">Sakit</td>
                            <td class="py-3">@if($report->surat_path)<a href="{{ asset('storage/' . $report->surat_path) }}" target="_blank" rel="noopener" class="text-blue-600 underline">Lihat foto</a>@else-@endif</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="py-4 text-gray-500">Belum ada laporan sakit hari ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
(() => {
    const data = @json($siswaPerKelas);
    const classInput = document.getElementById('sick-class');
    const studentInput = document.getElementById('sick-student');
    classInput.addEventListener('change', () => {
        const students = data[classInput.value] || [];
        studentInput.replaceChildren(new Option(students.length ? '-- Pilih Siswa --' : '-- Tidak ada siswa --', ''));
        students.forEach(student => studentInput.add(new Option(student.nis ? `${student.nama} (${student.nis})` : student.nama, student.id)));
        studentInput.disabled = !students.length;
    });
})();
</script>
@endsection
