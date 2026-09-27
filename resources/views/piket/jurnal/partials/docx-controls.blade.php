<section class="rekap-download">
    <div>
        <h3 class="rekap-download-title">Unduh laporan {{ $judulObjek }}</h3>
        <p class="rekap-download-note">Dokumen hanya memuat jurnal untuk pilihan ini pada periode yang ditentukan.</p>
    </div>
    <form action="{{ route('piket.jurnal.rekap.docx') }}" method="GET" class="rekap-download-form">
        <input type="hidden" name="jenis" value="{{ $jenis }}">
        <input type="hidden" name="objek" value="{{ $jenis === 'kelas' ? $objek->id_kelas : $objek->id_guru }}">
        <label class="rekap-field">Dari tanggal<input class="rekap-date" type="date" name="mulai" value="{{ now()->startOfMonth()->toDateString() }}" required></label>
        <label class="rekap-field">Sampai tanggal<input class="rekap-date" type="date" name="sampai" value="{{ now()->toDateString() }}" required></label>
        <button class="rekap-button" type="submit"><span class="material-symbols-outlined">download</span>Unduh DOCX</button>
    </form>
</section>
