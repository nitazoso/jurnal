@extends('layouts.piket')

@section('content')

<div class="p-6">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            Edit Dispen
        </h1>
        <p class="text-gray-500 mt-1">
            Perbarui data dispensasi siswa
        </p>
    </div>

    @if ($errors->any())
        <div class="mb-4 p-4 rounded-lg bg-red-100 text-red-700">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow p-6">

        <form action="{{ route('piket.dispen.update', $dispen->id_dispen) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block mb-2 font-medium text-gray-700">Kelas</label>
                <select name="id_kelas" id="class-select"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2"
                        required>
                    <option value="">-- Pilih Kelas Terlebih Dahulu --</option>
                    @foreach ($kelases as $kelas)
                        <option value="{{ $kelas->id_kelas }}"
                            {{ old('id_kelas', $dispen->siswa?->id_kelas) == $kelas->id_kelas ? 'selected' : '' }}>
                            {{ $kelas->nama_kelas }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-medium text-gray-700">Siswa</label>
                <select name="id_siswa" id="student-select"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2" required disabled>
                    <option value="">-- Pilih kelas terlebih dahulu --</option>
                </select>
                <p id="student-help" class="mt-1 text-sm text-gray-500">Pilih kelas untuk menampilkan seluruh siswa.</p>
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-medium text-gray-700">Kirim ke Kesiswaan</label>
                <select name="id_kesiswaan" class="w-full border border-gray-300 rounded-lg px-4 py-2" required>
                    <option value="">-- Pilih Kesiswaan --</option>
                    @foreach ($petugasKesiswaans as $petugas)
                        <option value="{{ $petugas->id_user }}"
                            {{ old('id_kesiswaan', $dispen->id_kesiswaan) == $petugas->id_user ? 'selected' : '' }}>
                            {{ $petugas->nama_user }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-medium text-gray-700">
                    Tanggal
                </label>

                <input type="date"
                       name="tanggal"
                       value="{{ old('tanggal', $dispen->tanggal->format('Y-m-d')) }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2"
                       required>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">

                <div>
                    <label class="block mb-2 font-medium text-gray-700">
                        Jam Mulai
                    </label>

                    <select name="id_jam_mulai"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2"
                            required>

                        @foreach ($jamPels as $jam)
                            <option value="{{ $jam->id_jam }}"
                                {{ old('id_jam_mulai', $dispen->id_jam_mulai) == $jam->id_jam ? 'selected' : '' }}>
                                Jam ke-{{ $jam->jam_ke }}
                                ({{ substr($jam->jam_mulai, 0, 5) }} -
                                {{ substr($jam->jam_selesai, 0, 5) }})
                            </option>
                        @endforeach

                    </select>
                </div>

                <div>
                    <label class="block mb-2 font-medium text-gray-700">
                        Jam Selesai
                    </label>

                    <select name="id_jam_selesai"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2"
                            required>

                        @foreach ($jamPels as $jam)
                            <option value="{{ $jam->id_jam }}"
                                {{ old('id_jam_selesai', $dispen->id_jam_selesai) == $jam->id_jam ? 'selected' : '' }}>
                                Jam ke-{{ $jam->jam_ke }}
                                ({{ substr($jam->jam_mulai, 0, 5) }} -
                                {{ substr($jam->jam_selesai, 0, 5) }})
                            </option>
                        @endforeach

                    </select>
                </div>

            </div>

            <div class="mb-6">
                <label class="block mb-2 font-medium text-gray-700">
                    Alasan Dispen
                </label>

                <textarea name="alasan"
                          rows="4"
                          class="w-full border border-gray-300 rounded-lg px-4 py-2"
                          required>{{ old('alasan', $dispen->alasan) }}</textarea>
            </div>

            <div class="flex gap-3">

                <a href="{{ route('piket.dispen.index') }}"
                   class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700">
                    Kembali
                </a>

                <button type="submit"
                        class="px-4 py-2 rounded-lg bg-[#30366f] text-white">
                    Simpan Perubahan
                </button>

            </div>

        </form>

    </div>

</div>

<script>
    (() => {
        const studentsByClass = @json($siswaPerKelas);
        const classSelect = document.getElementById('class-select');
        const studentSelect = document.getElementById('student-select');
        const studentHelp = document.getElementById('student-help');
        const selectedStudent = @json((string) old('id_siswa', $dispen->id_siswa));

        const showStudents = (classId, studentId = '') => {
            const students = studentsByClass[classId] || [];
            studentSelect.replaceChildren(new Option(
                students.length ? '-- Pilih Siswa --' : '-- Tidak ada siswa di kelas ini --', ''
            ));
            students.forEach((student) => {
                const label = student.nis ? `${student.nama} (${student.nis})` : student.nama;
                studentSelect.add(new Option(label, student.id, false, String(student.id) === String(studentId)));
            });
            studentSelect.disabled = !classId || !students.length;
            studentHelp.textContent = classId
                ? `${students.length} siswa ditemukan di kelas ini.`
                : 'Pilih kelas untuk menampilkan seluruh siswa.';
        };

        classSelect.addEventListener('change', () => showStudents(classSelect.value));
        if (classSelect.value) showStudents(classSelect.value, selectedStudent);
    })();
</script>

@endsection
