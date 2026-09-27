<?php

namespace App\Services;

class PiketJurnalDocx
{
    public function make(array $data): array
    {
        $esc = static fn ($value) => htmlspecialchars((string) $value, ENT_XML1 | ENT_QUOTES, 'UTF-8');
        $p = static function ($value, $bold = false, $size = 20) use ($esc) {
            return '<w:p><w:pPr><w:spacing w:after="100"/></w:pPr><w:r><w:rPr>' . ($bold ? '<w:b/>' : '') . '<w:sz w:val="' . $size . '"/></w:rPr><w:t xml:space="preserve">' . $esc($value) . '</w:t></w:r></w:p>';
        };
        $cell = static fn ($value, $header = false) => '<w:tc><w:tcPr><w:tcW w:w="2200" w:type="dxa"/>' . ($header ? '<w:shd w:fill="DCE6F1"/>' : '') . '</w:tcPr>' . $p($value, $header, 16) . '</w:tc>';
        $headers = ['Tanggal', 'Jam', 'Mata Pelajaran', 'Materi', 'Hadir', 'Tidak Hadir', 'Validasi'];
        $rows = '<w:tr>' . implode('', array_map(fn ($v) => $cell($v, true), $headers)) . '</w:tr>';
        foreach ($data['jurnals'] as $jurnal) {
            $absensi = $jurnal->detailAbsensis;
            $hadir = $absensi->filter(fn ($item) => mb_strtolower((string) $item->status) === 'hadir')->count();
            $tidakHadir = $absensi->reject(fn ($item) => mb_strtolower((string) $item->status) === 'hadir')->map(function ($item) {
                $name = $item->siswa->nama_siswa ?? 'Siswa';
                return $name . ' (' . $item->status . ($item->keterangan ? ': ' . $item->keterangan : '') . ')';
            })->implode(', ');
            $jam = trim(($jurnal->jamMulai->jam ?? '') . ' - ' . ($jurnal->jamSelesai->jam ?? ''));
            $values = [$jurnal->tanggal, $jam, $jurnal->jadwal->mapel->nama_mapel ?? '-', $jurnal->materi ?? '-', $hadir, $tidakHadir ?: '-', $jurnal->status_validasi_guru ?? '-'];
            $rows .= '<w:tr>' . implode('', array_map(fn ($v) => $cell($v), $values)) . '</w:tr>';
        }
        $document = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><w:document xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"><w:body>';
        $document .= $p('REKAP AKTIVITAS JURNAL', true, 32) . $p($data['judul'], true, 26) . $p('Periode: ' . $data['mulai']->format('d-m-Y') . ' s.d. ' . $data['sampai']->format('d-m-Y'));
        $document .= $p('Total jurnal: ' . $data['jurnals']->count() . ' | Hadir: ' . $data['totalHadir'] . ' | Izin/Sakit/Alpha: ' . $data['totalTidakHadir']);
        $document .= '<w:tbl><w:tblPr><w:tblW w:w="0" w:type="auto"/><w:tblBorders><w:top w:val="single" w:sz="4"/><w:left w:val="single" w:sz="4"/><w:bottom w:val="single" w:sz="4"/><w:right w:val="single" w:sz="4"/><w:insideH w:val="single" w:sz="4"/><w:insideV w:val="single" w:sz="4"/></w:tblBorders></w:tblPr><w:tblGrid>' . str_repeat('<w:gridCol w:w="2200"/>', count($headers)) . '</w:tblGrid>' . $rows . '</w:tbl><w:sectPr><w:pgSz w:w="16838" w:h="11906" w:orient="landscape"/><w:pgMar w:top="720" w:right="720" w:bottom="720" w:left="720"/></w:sectPr></w:body></w:document>';
        return [
            '[Content_Types].xml' => '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types"><Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/><Default Extension="xml" ContentType="application/xml"/><Override PartName="/word/document.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.document.main+xml"/><Override PartName="/word/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.styles+xml"/></Types>',
            '_rels/.rels' => '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="word/document.xml"/></Relationships>',
            'word/_rels/document.xml.rels' => '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/></Relationships>',
            'word/styles.xml' => '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><w:styles xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"><w:style w:type="paragraph" w:default="1" w:styleId="Normal"><w:name w:val="Normal"/><w:rPr><w:sz w:val="20"/></w:rPr></w:style></w:styles>',
            'word/document.xml' => $document,
        ];
    }
}
