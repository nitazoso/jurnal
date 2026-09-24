<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Kelas {{ $kelas->nama_kelas }}</title>
    <style>
        body { color: #172033; font-family: Arial, sans-serif; margin: 0; }
        .sheet { align-items: center; border: 3px solid #172033; display: flex; flex-direction: column; justify-content: center; margin: 20mm auto; min-height: 150mm; padding: 16mm; text-align: center; width: 110mm; }
        h1 { font-size: 30px; margin: 0 0 8px; }
        p { color: #475569; font-size: 16px; margin: 6px 0; }
        img { height: 75mm; margin: 16px 0; width: 75mm; }
        .note { font-size: 13px; }
        .print-button, .back-button { border: 0; border-radius: 8px; color: #fff; cursor: pointer; font-weight: 700; margin-top: 18px; padding: 10px 18px; }
        .print-button { background: #172033; }
        .back-button { background: #64748b; margin-right: 10px; text-decoration: none; display: inline-block; }
        .action-row { display: flex; gap: 10px; justify-content: center; align-items: center; flex-wrap: wrap; }
        @media print { .print-button, .back-button { display: none; } .sheet { margin: 0 auto; } }
    </style>
</head>
<body>
    <main class="sheet">
        <h1>{{ $kelas->nama_kelas }}</h1>
        <p>QR Kehadiran Guru</p>
        <img src="{{ route('admin.kelas.qr', $kelas) }}" alt="QR kelas {{ $kelas->nama_kelas }}">
        <p class="note">Scan QR ini saat mengisi jurnal di kelas.</p>
        <div class="action-row">
            <a href="{{ route('admin.kelas.index') }}" class="back-button">Kembali</a>
            <button class="print-button" type="button" onclick="window.print()">Cetak</button>
        </div>
    </main>
</body>
</html>