<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\PiketJadwal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;
use Throwable;

class JadwalPiketImportController extends Controller
{
    public function create()
    {
        return view('admin.jadwal-piket.import');
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:pdf', 'max:20480'],
            'confirm_replace' => ['required', 'accepted'],
        ]);

        try {
            $parsed = $this->readScannedPdf($request->file('file')->getRealPath());
        } catch (Throwable $exception) {
            $message = str_contains(strtolower($exception->getMessage()), 'tesseract')
                ? 'Fitur baca PDF scan membutuhkan Tesseract OCR pada server. Pasang tesseract-ocr dan bahasa Inggris (eng), lalu coba lagi.'
                : 'PDF tidak dapat dibaca. Pastikan PDF berisi tabel jadwal yang jelas dan tidak terkunci.';

            return back()->withErrors(['file' => $message]);
        }

        $entries = $parsed['entries'];
        $failures = $parsed['failures'];
        $month = $parsed['month'];

        if (! $month) {
            $failures[] = ['row' => '—', 'reason' => 'Bulan jadwal tidak berhasil dikenali dari PDF.'];
        }

        if ($entries === [] && $failures === []) {
            $failures[] = ['row' => '—', 'reason' => 'Tidak ada tanggal jadwal yang dikenali pada PDF.'];
        }

        $replaced = 0;
        if ($failures === [] && $entries !== [] && $month) {
            [$startDate, $endDate] = [$month->copy()->startOfMonth(), $month->copy()->endOfMonth()];
            $hours = $this->piketHours();

            DB::transaction(function () use ($entries, $startDate, $endDate, $hours, &$replaced): void {
                PiketJadwal::query()
                    ->whereBetween('tanggal', [$startDate->toDateString(), $endDate->toDateString()])
                    ->delete();

                foreach ($entries as $entry) {
                    $base = [
                        'tanggal' => $entry['tanggal'],
                        'created_by' => auth()->user()?->id_user,
                        'keterangan' => 'Diimpor dari PDF jadwal piket',
                    ];

                    foreach ($entry['pagi_petugas'] as $guruId) {
                        PiketJadwal::create($base + [
                            'id_guru' => $guruId,
                            'shift' => 'Pagi',
                            'jam_mulai' => $hours['jam_mulai_pagi'],
                            'jam_selesai' => $hours['jam_selesai_pagi'],
                            'jenis_tugas' => 'Piket KBM Pagi',
                            'posisi' => 'Petugas',
                        ]);
                        $replaced++;
                    }

                    PiketJadwal::create($base + [
                        'id_guru' => $entry['pagi_koordinator'],
                        'shift' => 'Pagi',
                        'jam_mulai' => $hours['jam_mulai_pagi'],
                        'jam_selesai' => $hours['jam_selesai_pagi'],
                        'jenis_tugas' => 'Koordinator Piket KBM Pagi',
                        'posisi' => 'Koordinator',
                    ]);
                    $replaced++;

                    foreach ($entry['siang_petugas'] as $guruId) {
                        PiketJadwal::create($base + [
                            'id_guru' => $guruId,
                            'shift' => 'Siang',
                            'jam_mulai' => $hours['jam_mulai_siang'],
                            'jam_selesai' => $hours['jam_selesai_siang'],
                            'jenis_tugas' => 'Piket KBM Siang',
                            'posisi' => 'Petugas',
                        ]);
                        $replaced++;
                    }

                    PiketJadwal::create($base + [
                        'id_guru' => $entry['siang_koordinator'],
                        'shift' => 'Siang',
                        'jam_mulai' => $hours['jam_mulai_siang'],
                        'jam_selesai' => $hours['jam_selesai_siang'],
                        'jenis_tugas' => 'Koordinator Piket KBM Siang',
                        'posisi' => 'Koordinator',
                    ]);
                    $replaced++;

                    PiketJadwal::create($base + [
                        'id_guru' => $entry['waka'],
                        'shift' => 'Waka',
                        'jam_mulai' => null,
                        'jam_selesai' => null,
                        'jenis_tugas' => 'Piket Waka',
                        'posisi' => 'Petugas',
                    ]);
                    $replaced++;
                }
            });
        }

        return redirect()
            ->route('admin.jadwal-piket.index', ['month' => $month?->format('Y-m') ?? now()->format('Y-m')])
            ->with('piket_import_result', [
                'processed' => $failures === [] ? $replaced : 0,
                'failures' => $failures,
                'aborted' => $failures !== [],
                'month' => $month?->translatedFormat('F Y'),
            ]);
    }

    private function readScannedPdf(string $path): array
    {
        $tempDir = sys_get_temp_dir().'/piket-ocr-'.Str::random(12);
        if (! mkdir($tempDir, 0700, true) && ! is_dir($tempDir)) {
            throw new \RuntimeException('Tidak dapat membuat folder OCR sementara.');
        }

        try {
            $info = new Process(['pdfinfo', $path]);
            $info->mustRun();
            preg_match('/^Pages:\s*(\d+)/mi', $info->getOutput(), $matches);
            $pageCount = (int) ($matches[1] ?? 0);
            if ($pageCount < 1 || $pageCount > 40) {
                throw new \RuntimeException('Jumlah halaman PDF tidak valid.');
            }

            $languageCheck = new Process(['tesseract', '--list-langs']);
            $languageCheck->mustRun();
            $languages = $languageCheck->getOutput();
            $language = str_contains($languages, 'ind') && str_contains($languages, 'eng') ? 'ind+eng' : 'eng';

            $prefix = $tempDir.'/page';
            $render = new Process(['pdftoppm', '-r', '220', '-png', '-f', '1', '-l', (string) $pageCount, $path, $prefix]);
            $render->setTimeout(180);
            $render->mustRun();

            $gurus = Guru::query()->get(['id_guru', 'nama_guru']);
            $entries = [];
            $failures = [];
            $months = [];
            $globalLine = 0;

            for ($page = 1; $page <= $pageCount; $page++) {
                $imagePath = $prefix.'-'.$page.'.png';
                if (! is_file($imagePath)) {
                    continue;
                }

                $dimensions = getimagesize($imagePath);
                if (! $dimensions) {
                    continue;
                }
                [$width, $height] = $dimensions;

                // Sparse-text mode reads the separate table cells in scanned schedules more reliably.
                $ocr = new Process(['tesseract', $imagePath, 'stdout', '-l', $language, '--psm', '11', 'tsv']);
                $ocr->setTimeout(180);
                $ocr->mustRun();
                $words = $this->tsvWords($ocr->getOutput(), $width, $height);
                $dateRows = $this->dateRows($words, $height);

                foreach ($dateRows as $rowIndex => $dateRow) {
                    $globalLine++;
                    if (! $dateRow['year']) {
                        $failures[] = ['row' => $globalLine, 'reason' => 'Tahun pada tanggal jadwal tidak terbaca.'];
                        continue;
                    }
                    $monthNumber = $dateRow['month'];
                    $date = Carbon::create($dateRow['year'], $monthNumber, $dateRow['day'])->startOfDay();
                    $months[$date->format('Y-m')] = true;
                    $expectedWeekday = $this->weekdayNumber($dateRow['weekday']);
                    if ($date->dayOfWeekIso !== $expectedWeekday) {
                        $failures[] = ['row' => $globalLine, 'reason' => 'Tanggal dan nama hari tidak cocok: '.$date->locale('id')->translatedFormat('l, d F Y').'.'];
                        continue;
                    }

                    $top = $dateRow['top'];
                    $bottom = $dateRow['bottom'];
                    $columns = [
                        'pagi_petugas' => [0.165, 0.345],
                        'pagi_koordinator' => [0.345, 0.493],
                        'siang_petugas' => [0.493, 0.675],
                        'siang_koordinator' => [0.675, 0.84],
                        'waka' => [0.84, 0.99],
                    ];
                    $matched = [];
                    $rowHasFailure = false;

                    foreach ($columns as $field => [$left, $right]) {
                        $texts = $this->cellLines($words, $top, $bottom, $left, $right);

                        if ($field === 'pagi_petugas' || $field === 'siang_petugas') {
                            $petugasResult = $this->matchGuruLines($texts, $gurus);
                            $matched[$field] = $petugasResult['ids'];
                            foreach ($petugasResult['unmatched'] as $text) {
                                $failures[] = ['row' => $globalLine, 'reason' => 'Guru tidak dikenali pada '.$date->locale('id')->translatedFormat('d F Y').' ('.$field.'): '.$text];
                                $rowHasFailure = true;
                            }
                            if (count($matched[$field]) !== 3) {
                                $failures[] = ['row' => $globalLine, 'reason' => 'Jumlah petugas harus 3 orang pada '.$date->locale('id')->translatedFormat('d F Y').' ('.$field.'). Terbaca '.count($matched[$field]).'.'];
                                $rowHasFailure = true;
                            }
                            continue;
                        }

                        $text = implode(' ', $texts);
                        $id = $this->matchGuru($text, $gurus);
                        if (! $id) {
                            $label = $field === 'pagi_koordinator' ? 'Koordinator pagi' : ($field === 'siang_koordinator' ? 'Koordinator siang' : 'Piket Waka');
                            $failures[] = ['row' => $globalLine, 'reason' => $label.' tidak dikenali pada '.$date->locale('id')->translatedFormat('d F Y').': '.($text ?: '(kosong)')];
                            $rowHasFailure = true;
                        }
                        $matched[$field] = $id;
                    }

                    if (! $rowHasFailure) {
                        $entries[] = ['tanggal' => $date->toDateString()] + $matched;
                    }
                }
            }

            if (count($months) > 1) {
                $failures[] = ['row' => '—', 'reason' => 'Satu file impor hanya boleh berisi satu bulan jadwal.'];
            }

            $monthKey = array_key_first($months);
            return [
                'entries' => $entries,
                'failures' => $failures,
                'month' => $monthKey ? Carbon::createFromFormat('Y-m', $monthKey)->startOfMonth() : null,
            ];
        } finally {
            foreach (glob($tempDir.'/*') ?: [] as $file) {
                @unlink($file);
            }
            @rmdir($tempDir);
        }
    }

    private function tsvWords(string $tsv, int $width, int $height): array
    {
        $lines = preg_split('/\r\n|\n|\r/', trim($tsv));
        $words = [];
        foreach (array_slice($lines ?: [], 1) as $line) {
            $parts = explode("\t", $line);
            if (count($parts) < 12 || (int) $parts[0] !== 5) {
                continue;
            }

            $text = trim($parts[11]);
            if ($text === '' || (float) $parts[10] < 20) {
                continue;
            }

            $left = (int) $parts[6];
            $top = (int) $parts[7];
            $words[] = [
                'text' => $text,
                'x' => ($left + ((int) $parts[8] / 2)) / $width,
                'y' => ($top + ((int) $parts[9] / 2)) / $height,
                'top' => $top,
                'line' => implode(':', array_slice($parts, 1, 4)),
            ];
        }

        return $words;
    }

    private function dateRows(array $words, int $height): array
    {
        $weekdays = [
            'senin' => 1, 'monday' => 1,
            'selasa' => 2, 'tuesday' => 2,
            'rabu' => 3, 'wednesday' => 3,
            'kamis' => 4, 'thursday' => 4,
            'jumat' => 5, 'jum' => 5, 'friday' => 5,
        ];
        $months = [
            'januari' => 1, 'january' => 1, 'februari' => 2, 'february' => 2,
            'maret' => 3, 'march' => 3, 'april' => 4, 'mei' => 5, 'may' => 5,
            'juni' => 6, 'june' => 6, 'juli' => 7, 'july' => 7, 'agustus' => 8,
            'august' => 8, 'september' => 9, 'oktober' => 10, 'october' => 10,
            'november' => 11, 'desember' => 12, 'december' => 12,
        ];
        $dateWords = array_values(array_filter($words, fn ($word) => $word['x'] >= 0.045 && $word['x'] < 0.17));
        $groups = [];
        foreach ($dateWords as $word) {
            $groups[$word['line']][] = $word;
        }

        $candidates = [];
        foreach ($groups as $group) {
            $text = strtolower(implode(' ', array_column($group, 'text')));
            $weekday = null;
            foreach ($weekdays as $name => $number) {
                if (preg_match('/\b'.preg_quote($name, '/').'\b/u', $text)) {
                    $weekday = $number;
                    break;
                }
            }
            if (! $weekday) {
                continue;
            }

            $month = null;
            foreach ($months as $name => $number) {
                if (preg_match('/\b'.preg_quote($name, '/').'\b/u', $text)) {
                    $month = $number;
                    break;
                }
            }
            if (! $month || ! preg_match('/\b(\d{1,2})\b/', $text, $dayMatch)) {
                continue;
            }

            $center = array_sum(array_column($group, 'y')) / count($group);
            $candidates[] = [
                'weekday' => $weekday,
                'day' => (int) $dayMatch[1],
                'month' => $month,
                'center' => $center,
                'year' => null,
            ];
        }

        usort($candidates, fn ($a, $b) => $a['center'] <=> $b['center']);
        $candidates = collect($candidates)->unique(fn ($row) => $row['day'].'-'.$row['month'])->values()->all();

        foreach ($candidates as $index => &$candidate) {
            $previous = $candidates[$index - 1]['center'] ?? null;
            $next = $candidates[$index + 1]['center'] ?? null;
            $gapBefore = $previous !== null ? $candidate['center'] - $previous : ($next !== null ? $next - $candidate['center'] : 0.11);
            $gapAfter = $next !== null ? $next - $candidate['center'] : $gapBefore;
            // The date label is printed in the upper part of each tall day cell,
            // so row boundaries are intentionally asymmetric around its baseline.
            $candidate['top'] = max(0, $candidate['center'] - ($gapBefore * 0.3));
            $candidate['bottom'] = min(1, $candidate['center'] + ($gapAfter * 0.7));
            $candidate['year'] = $this->yearNearRow($words, $candidate['center'], $candidate['top'], $candidate['bottom'], $height);
        }
        unset($candidate);

        return $candidates;
    }

    private function yearNearRow(array $words, float $center, float $top, float $bottom, int $height): ?int
    {
        $year = collect($words)->first(function ($word) use ($top, $bottom) {
            return $word['x'] >= 0.045 && $word['x'] < 0.17
                && $word['y'] >= $top && $word['y'] <= $bottom
                && preg_match('/^20\d{2}$/', $word['text']);
        });

        if ($year) {
            return (int) $year['text'];
        }

        $headerYears = collect($words)
            ->filter(fn ($word) => preg_match('/^20\d{2}$/', $word['text']) && $word['y'] < 0.2)
            ->sortBy('y')
            ->first();

        return $headerYears ? (int) $headerYears['text'] : null;
    }

    private function cellLines(array $words, float $top, float $bottom, float $left, float $right): array
    {
        $inside = array_values(array_filter($words, fn ($word) =>
            $word['x'] >= $left && $word['x'] < $right && $word['y'] >= $top && $word['y'] <= $bottom
        ));
        usort($inside, fn ($a, $b) => $a['y'] <=> $b['y'] ?: $a['x'] <=> $b['x']);

        $groups = [];
        foreach ($inside as $word) {
            $groupIndex = null;
            foreach ($groups as $index => $group) {
                if (abs($word['y'] - $group['y']) <= 0.012) {
                    $groupIndex = $index;
                    break;
                }
            }

            if ($groupIndex === null) {
                $groups[] = ['y' => $word['y'], 'words' => [$word]];
            } else {
                $groups[$groupIndex]['words'][] = $word;
                $groups[$groupIndex]['y'] = array_sum(array_column($groups[$groupIndex]['words'], 'y')) / count($groups[$groupIndex]['words']);
            }
        }

        $lines = [];
        foreach ($groups as $group) {
            $lineWords = $group['words'];
            usort($lineWords, fn ($a, $b) => $a['x'] <=> $b['x']);
            $text = trim(implode(' ', array_column($lineWords, 'text')));
            if ($text === '' || preg_match('/\b\d{1,2}[.:]\d{2}\b/', $text)) {
                continue;
            }
            $lines[] = ['y' => $group['y'], 'text' => $text];
        }

        usort($lines, fn ($a, $b) => $a['y'] <=> $b['y']);
        return array_column($lines, 'text');
    }

    private function matchGuruLines(array $lines, $gurus): array
    {
        $ids = [];
        $unmatched = [];
        $suffixes = ['s pd', 'm pd', 's pd i', 'm pd i', 's si', 'm si', 's sn', 'm sn', 's kom', 'm kom', 's psi', 'm psi', 's ag', 'm ag', 's e', 'm e', 's st', 'm st', 's ds', 'm t', 's st par'];

        for ($index = 0; $index < count($lines); $index++) {
            $text = trim($lines[$index]);
            $normalized = $this->normalizeGuruName($text);
            if ($normalized === '' || in_array($normalized, $suffixes, true)) {
                continue;
            }

            $id = $this->matchGuru($text, $gurus);
            if ($id) {
                $ids[] = $id;
                continue;
            }

            $next = $lines[$index + 1] ?? '';
            $combinedId = $next !== '' ? $this->matchGuru($text.' '.$next, $gurus) : null;
            if ($combinedId) {
                $ids[] = $combinedId;
                $index++;
                continue;
            }

            $unmatched[] = $text;
        }

        return ['ids' => $ids, 'unmatched' => $unmatched];
    }

    private function matchGuru(string $text, $gurus): ?int
    {
        $needle = $this->normalizeGuruName($text);
        if ($needle === '') {
            return null;
        }

        foreach (array_filter(explode(' ', $needle), fn ($token) => strlen($token) >= 7) as $token) {
            $tokenMatches = $gurus->filter(function ($guru) use ($token) {
                return in_array($token, explode(' ', $this->normalizeGuruName($guru->nama_guru)), true);
            });
            if ($tokenMatches->count() === 1) {
                return (int) $tokenMatches->first()->id_guru;
            }
        }

        $ranked = [];
        foreach ($gurus as $guru) {
            $candidate = $this->normalizeGuruName($guru->nama_guru);
            if ($candidate === $needle) {
                return (int) $guru->id_guru;
            }

            similar_text($needle, $candidate, $similarity);
            $distance = levenshtein($needle, $candidate);
            $ratio = 1 - ($distance / max(strlen($needle), strlen($candidate), 1));
            $ranked[] = ['id' => (int) $guru->id_guru, 'score' => max($similarity / 100, $ratio)];
        }

        usort($ranked, fn ($a, $b) => $b['score'] <=> $a['score']);
        if (($ranked[0]['score'] ?? 0) < 0.78) {
            return null;
        }

        if ((($ranked[0]['score'] ?? 0) - ($ranked[1]['score'] ?? 0)) < 0.04) {
            $topCandidates = $gurus->filter(function ($guru) use ($needle, $ranked) {
                $candidate = $this->normalizeGuruName($guru->nama_guru);
                similar_text($needle, $candidate, $similarity);
                $distance = levenshtein($needle, $candidate);
                $score = max($similarity / 100, 1 - ($distance / max(strlen($needle), strlen($candidate), 1)));
                return abs($score - $ranked[0]['score']) < 0.000001;
            })->map(fn ($guru) => $this->normalizeGuruName($guru->nama_guru))->unique();

            if ($topCandidates->count() !== 1) {
                return null;
            }
        }

        return $ranked[0]['id'];
    }

    private function normalizeGuruName(string $name): string
    {
        $name = Str::ascii(mb_strtolower(trim($name)));
        $name = preg_replace('/[,;].*$/', '', $name) ?? $name;
        return trim(preg_replace('/[^a-z0-9]+/', ' ', $name) ?? '', ' ');
    }

    private function weekdayNumber(int $weekday): int
    {
        return $weekday;
    }

    private function piketHours(): array
    {
        $defaults = [
            'jam_mulai_pagi' => '07:00',
            'jam_selesai_pagi' => '11:00',
            'jam_mulai_siang' => '11:00',
            'jam_selesai_siang' => '15:00',
        ];

        if (! DB::getSchemaBuilder()->hasTable('app_settings')) {
            return $defaults;
        }

        foreach ($defaults as $key => $value) {
            $defaults[$key] = DB::table('app_settings')->where('key', 'piket.'.$key)->value('value') ?? $value;
        }

        return $defaults;
    }
}
