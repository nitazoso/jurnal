<?php

namespace App\Exports;

use App\Models\PiketJadwal;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class JadwalPiketExport implements FromArray, WithColumnWidths, WithEvents, WithTitle
{
    private Collection $jadwalsByDate;

    public function __construct(private readonly Carbon $month)
    {
        $start = $month->copy()->startOfMonth()->toDateString();
        $end = $month->copy()->endOfMonth()->toDateString();

        $this->jadwalsByDate = PiketJadwal::query()
            ->with('guru:id_guru,nama_guru')
            ->whereBetween('tanggal', [$start, $end])
            ->orderBy('tanggal')
            ->orderBy('id_piket_jadwal')
            ->get()
            ->groupBy(fn (PiketJadwal $jadwal) => $jadwal->tanggal->toDateString());
    }

    public function array(): array
    {
        $rows = [
            ['JADWAL PIKET BULAN '.mb_strtoupper($this->month->copy()->locale('id')->translatedFormat('F Y'))],
            ['Petugas KBM pagi dan siang, koordinator, serta piket Waka'],
            [
                'HARI / TANGGAL',
                'PETUGAS PIKET KBM PAGI',
                'KOORDINATOR PAGI',
                'PETUGAS PIKET KBM SIANG',
                'KOORDINATOR SIANG',
                'PIKET WAKA',
            ],
        ];

        $firstDay = $this->month->copy()->startOfMonth();
        $lastDay = $this->month->copy()->endOfMonth();

        for ($date = $firstDay; $date->lte($lastDay); $date->addDay()) {
            if ($date->isWeekend()) {
                continue;
            }

            $entries = $this->jadwalsByDate->get($date->toDateString(), collect());
            $rows[] = [
                $date->copy()->locale('id')->translatedFormat('l, d F Y'),
                $this->namesFor($entries, 'Piket KBM Pagi'),
                $this->namesFor($entries, 'Koordinator Piket KBM Pagi', true),
                $this->namesFor($entries, 'Piket KBM Siang'),
                $this->namesFor($entries, 'Koordinator Piket KBM Siang', true),
                $this->namesFor($entries, 'Piket Waka'),
            ];
        }

        return $rows;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 26,
            'B' => 30,
            'C' => 26,
            'D' => 30,
            'E' => 26,
            'F' => 26,
        ];
    }

    public function title(): string
    {
        return 'Jadwal Piket';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event): void {
                $sheet = $event->sheet->getDelegate();
                $lastRow = max(3, $sheet->getHighestRow());

                $sheet->mergeCells('A1:F1');
                $sheet->mergeCells('A2:F2');
                $sheet->setCellValue('A1', 'JADWAL PIKET BULAN '.mb_strtoupper($this->month->copy()->locale('id')->translatedFormat('F Y')));
                $sheet->setCellValue('A2', 'Petugas KBM pagi dan siang, koordinator, serta piket Waka');
                $sheet->getStyle('A1:F2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A1:F2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                $sheet->getStyle('A1:F1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 18, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1B234A']],
                ]);
                $sheet->getStyle('A2:F2')->applyFromArray([
                    'font' => ['italic' => true, 'size' => 10, 'color' => ['rgb' => '475569']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'EEF2FF']],
                ]);
                $sheet->getRowDimension(1)->setRowHeight(34);
                $sheet->getRowDimension(2)->setRowHeight(22);

                $sheet->getStyle('A3:F3')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 10, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '30366F']],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                ]);
                $sheet->getRowDimension(3)->setRowHeight(34);

                $sheet->getStyle("A3:F{$lastRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CBD5E1']],
                    ],
                ]);

                if ($lastRow >= 4) {
                    $sheet->getStyle("A4:F{$lastRow}")->getAlignment()
                        ->setVertical(Alignment::VERTICAL_CENTER)
                        ->setWrapText(true);

                    for ($row = 4; $row <= $lastRow; $row++) {
                        if ($row % 2 === 0) {
                            $sheet->getStyle("A{$row}:F{$row}")->getFill()
                                ->setFillType(Fill::FILL_SOLID)
                                ->getStartColor()->setRGB('F8FAFC');
                        }

                        $sheet->getStyle("A{$row}")->applyFromArray([
                            'font' => ['bold' => true, 'color' => ['rgb' => '1B234A']],
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'EEF2FF']],
                            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                        ]);

                        $lineCount = 1;
                        foreach (['B', 'C', 'D', 'E', 'F'] as $column) {
                            $lineCount = max($lineCount, substr_count((string) $sheet->getCell("{$column}{$row}")->getValue(), "\n") + 1);
                        }
                        $sheet->getRowDimension($row)->setRowHeight(max(34, min(72, 14 * $lineCount + 12)));
                    }
                }

                $sheet->freezePane('B4');
                $sheet->setAutoFilter("A3:F{$lastRow}");
                $sheet->setShowGridlines(false);
                $sheet->getPageSetup()
                    ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE)
                    ->setPaperSize(PageSetup::PAPERSIZE_A3)
                    ->setFitToWidth(1)
                    ->setFitToHeight(0);
                $sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 3);
                $sheet->getPageMargins()->setTop(0.35)->setRight(0.25)->setBottom(0.35)->setLeft(0.25);
                $sheet->getPageSetup()->setPrintArea("A1:F{$lastRow}");
            },
        ];
    }

    private function namesFor(Collection $entries, string $task, bool $firstOnly = false): string
    {
        $names = $entries
            ->where('jenis_tugas', $task)
            ->when($firstOnly, fn (Collection $entries) => $entries->take(1))
            ->map(fn (PiketJadwal $entry) => trim((string) ($entry->guru?->nama_guru ?? '')))
            ->filter()
            ->values();

        return $names->implode("\n");
    }
}
