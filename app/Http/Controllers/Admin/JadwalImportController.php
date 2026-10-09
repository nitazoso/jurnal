<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\JamPel;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Mapel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\Process\Process;
use DOMDocument;
use DOMElement;
use DOMXPath;
use Throwable;

class JadwalImportController extends Controller
{
    public function create()
    {
        return view('admin.jadwal.import', $this->options());
    }

    public function preview(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv,pdf|max:10240',
            'semester' => 'required|in:Ganjil,Genap',
            'tahun_ajaran' => 'required|regex:/^\d{4}\/\d{4}$/',
        ]);

        try {
            $data = [];
            if (strtolower($request->file('file')->getClientOriginalExtension()) === 'pdf') {
                $data = $this->readTimetablePdf($request->file('file')->getRealPath());
            } else {
                $spreadsheet = IOFactory::load($request->file('file')->getRealPath());
                foreach ($spreadsheet->getAllSheets() as $sheet) {
                    $rows = $sheet->toArray(null, true, true, false);
                    array_push($data, ...$this->readSheet($rows));
                }
            }
        } catch (Throwable $exception) {
            return back()->withErrors(['file' => 'File tidak dapat dibaca. Untuk PDF, pastikan PDF berisi teks yang dapat disalin (bukan hasil scan gambar).']);
        }

        if (count($data) === 0) {
            return back()->withErrors(['file' => 'Tidak ada baris jadwal yang dikenali. Pastikan file memuat kolom kelas, hari, mata pelajaran, dan guru.']);
        }
        foreach ($data as &$row) {
            $row['semester'] = $row['semester'] ?: $request->input('semester');
            $row['tahun_ajaran'] = $row['tahun_ajaran'] ?: $request->input('tahun_ajaran');
            $row['include'] = $row['include'] ?? 1;
        }
        unset($row);

<<<<<<< HEAD
        return $this->processRows($data, $data, true);
=======
        $token = Str::random(40);
        Cache::put($this->importCacheKey($token), $data, now()->addHours(2));
        // Keep the session copy for compatibility with preview links created before this change.
        $request->session()->put('jadwal_import.'.$token, $data);

        return redirect()->route('admin.jadwal.import.preview', $token);
>>>>>>> origin/dev
    }

    public function showPreview(Request $request, string $token)
    {
        $rows = Cache::get($this->importCacheKey($token))
            ?? $request->session()->get('jadwal_import.'.$token);
        if (! is_array($rows)) {
            return redirect()->route('admin.jadwal.import')
                ->withErrors(['file' => 'Data preview sudah kedaluwarsa atau sesi berubah. Silakan unggah ulang file jadwal.']);
        }

        $existingCount = Jadwal::count();

        return view('admin.jadwal.import-preview', $this->options() + compact('rows', 'token', 'existingCount'));
    }

    public function store(Request $request, string $token)
    {
        $rows = Cache::get($this->importCacheKey($token))
            ?? $request->session()->get('jadwal_import.'.$token);
        if (! is_array($rows)) {
            return redirect()->route('admin.jadwal.import')
                ->withErrors(['file' => 'Data preview sudah kedaluwarsa atau sesi berubah. Silakan unggah ulang file jadwal.']);
        }
        $validated = $request->validate(['rows_json' => 'required|string|max:5000000']);
        $submittedRows = json_decode($validated['rows_json'], true);
        if (! is_array($submittedRows) || count($submittedRows) !== count($rows)) {
            return back()->withErrors(['rows_json' => 'Data preview tidak lengkap. Muat ulang preview lalu coba lagi.']);
        }

        return $this->processRows($submittedRows, $rows, $request->boolean('confirm_replace'));
    }

    private function processRows(array $submittedRows, array $rows, bool $replaceExisting)
    {
        $includedRows = collect($submittedRows)->filter(fn ($row) => (string) ($row['include'] ?? '0') === '1');
        $periods = $includedRows
            ->map(fn ($row) => ($row['semester'] ?? '').' '.($row['tahun_ajaran'] ?? ''))
            ->unique()
            ->values();
        $replacementAborted = false;
        $seen = [];
        $processed = 0;
        $failures = [];
        $pendingRows = [];

        if (! $replaceExisting) {
            $replacementAborted = true;
            $failures[] = [
                'row' => '—',
                'reason' => 'Konfirmasi penggantian seluruh jadwal belum dicentang.',
                'source' => '',
            ];
        }

        // All current schedules will be replaced after validation, so conflict
        // checks only compare rows within the uploaded file.
        $existing = collect();

        foreach ($submittedRows as $index => $row) {
            $line = $index + 1;
            $sourceRow = $rows[$index] ?? [];
            $source = trim(implode(' · ', array_filter([
                $sourceRow['kelas_text'] ?? '', $sourceRow['hari'] ?? '', $sourceRow['jam_text'] ?? '',
                $sourceRow['mapel_text'] ?? '', $sourceRow['guru_text'] ?? '',
            ])));
            if ((string) ($row['include'] ?? '0') !== '1') { $failures[] = ['row' => $line, 'reason' => 'Baris dilewati', 'source' => $source]; continue; }
            $issues = [];
            $guru = Guru::find($row['id_guru'] ?? null);
            $kelas = Kelas::find($row['id_kelas'] ?? null);
            $mapel = Mapel::find($row['id_mapel'] ?? null);
            $start = JamPel::find($row['id_jam_mulai'] ?? null);
            $end = JamPel::find($row['id_jam_selesai'] ?? null);
            if (! $guru) $issues[] = 'Guru tidak ditemukan';
            if (! $kelas) $issues[] = 'Kelas tidak ditemukan';
            if (! $mapel) $issues[] = 'Mata pelajaran tidak ditemukan';
            if (! $start || ! $end) $issues[] = 'Jam pelajaran belum valid';
            if (! in_array($row['hari'] ?? '', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'], true)) $issues[] = 'Hari tidak valid';
            if (! in_array($row['semester'] ?? '', ['Ganjil', 'Genap'], true)) $issues[] = 'Semester tidak valid';
            if (! preg_match('/^\d{4}\/\d{4}$/', $row['tahun_ajaran'] ?? '')) $issues[] = 'Tahun ajaran tidak valid';
            if ($issues) { $failures[] = ['row' => $line, 'reason' => implode(', ', $issues), 'source' => $source]; continue; }

            $values = [
                'id_guru' => $guru->id_guru, 'id_mapel' => $mapel->id_mapel, 'id_kelas' => $kelas->id_kelas,
                'id_jam_mulai' => $start->id_jam, 'id_jam_selesai' => $end->id_jam, 'hari' => $row['hari'],
                'semester' => $row['semester'], 'tahun_ajaran' => $row['tahun_ajaran'],
            ];
            $key = implode(':', [$values['id_kelas'], $values['hari'], $values['id_jam_mulai'], $values['id_jam_selesai'], $values['semester'], $values['tahun_ajaran']]);
            if (isset($seen[$key]) || $existing->contains(fn ($item) => $item->id_kelas == $values['id_kelas'] && $item->hari === $values['hari'] && $item->id_jam_mulai == $values['id_jam_mulai'] && $item->id_jam_selesai == $values['id_jam_selesai'] && $item->semester === $values['semester'] && $item->tahun_ajaran === $values['tahun_ajaran'])) {
                $failures[] = ['row' => $line, 'reason' => 'Jadwal duplikat untuk kelas dan jam tersebut', 'source' => $source]; continue;
            }
            $conflict = $existing->first(fn ($item) => $item->hari === $values['hari'] && ($item->id_guru == $values['id_guru'] || $item->id_kelas == $values['id_kelas']) && $item->semester === $values['semester'] && $item->tahun_ajaran === $values['tahun_ajaran'] && $this->overlaps($start, $end, $item->jamMulai, $item->jamSelesai));
            if ($conflict) { $failures[] = ['row' => $line, 'reason' => $conflict->id_guru == $values['id_guru'] ? 'Bentrok jadwal guru' : 'Bentrok jadwal kelas', 'source' => $source]; continue; }

            $pending = new Jadwal($values);
            $pending->setRelation('jamMulai', $start);
            $pending->setRelation('jamSelesai', $end);
            $existing->push($pending);
            $pendingRows[] = $values;
            $seen[$key] = true;
        }

        $blockingFailures = collect($failures)->contains(fn ($failure) => $failure['reason'] !== 'Baris dilewati');
        if ($blockingFailures || $replacementAborted || $pendingRows === []) {
            $replacementAborted = true;
        } else {
            DB::transaction(function () use ($pendingRows): void {
                Jadwal::query()->delete();

                foreach ($pendingRows as $values) {
                    Jadwal::create($values);
                }
            });
            $processed = count($pendingRows);
        }

        // Keep the preview available until its two-hour cache TTL expires.
        // This lets admins revisit and correct rows after a partial/failed import;
        // duplicate checks prevent an accidental second insert.
        $replacedPeriods = $periods->implode(', ');

        return redirect()
            ->route('admin.jadwal.index')
            ->with('jadwal_import_result', [
                'processed' => $processed,
                'failures' => $failures,
                'replace_existing' => $replaceExisting,
                'replacement_aborted' => $replacementAborted,
                'replaced_periods' => $replacedPeriods,
            ]);
    }

    private function options(): array
    {
        return ['gurus' => Guru::orderBy('nama_guru')->get(), 'kelases' => Kelas::orderBy('nama_kelas')->get(), 'mapels' => Mapel::orderBy('nama_mapel')->get(), 'jamPels' => JamPel::where('jenis', 'pelajaran')->orderBy('jam_mulai')->get()];
    }

    /** Read aSc Timetables PDFs: one class per page, weekdays as rows and lesson periods as columns. */
    private function readTimetablePdf(string $path): array
    {
        $info = new Process(['pdfinfo', $path]);
        $info->mustRun();
        preg_match('/^Pages:\s*(\d+)/mi', $info->getOutput(), $pageMatch);
        $pageCount = min((int) ($pageMatch[1] ?? 0), 100);
        if ($pageCount < 1) return [];

        $textProcess = new Process(['pdftotext', '-bbox-layout', $path, '-']);
        $textProcess->mustRun();
        $document = new DOMDocument();
        @$document->loadHTML('<?xml encoding="UTF-8">'.$textProcess->getOutput());
        $xpath = new DOMXPath($document);
        $pages = $xpath->query('//page');
        if (! $pages || $pages->length === 0) return [];

        $gurus = Guru::all(); $mapels = Mapel::all(); $kelases = Kelas::all();
        $jamPels = JamPel::where('jenis', 'pelajaran')->get();
        $result = [];
        for ($pageIndex = 0; $pageIndex < min($pageCount, $pages->length); $pageIndex++) {
            $pageNode = $pages->item($pageIndex);
            $lines = $this->bboxLines($xpath, $pageNode);
            if (! $lines) continue;
            $classLine = collect($lines)->first(fn ($line) => $line['y'] < 70 && preg_match('/\b(?:X|XI|XII)\s+[A-Z0-9][A-Z0-9 .-]*\b/i', $line['text']));
            $className = $classLine['text'] ?? '';
            $kelasId = $this->matchMaster($className, $kelases, 'nama_kelas', 'id_kelas');
            $semester = 'Ganjil'; $tahunAjaran = '';
            foreach ($lines as $line) {
                if (preg_match('/SEMESTER\s+(GANJIL|GENAP).*?(20\d{2})\s*[-\/]\s*(20\d{2})/i', $line['text'], $meta)) {
                    $semester = ucfirst(strtolower($meta[1])); $tahunAjaran = $meta[2].'/'.$meta[3]; break;
                }
            }

            $svgPath = tempnam(sys_get_temp_dir(), 'jurnal-svg-');
            try {
                $svg = new Process(['pdftocairo', '-svg', '-f', (string) ($pageIndex + 1), '-l', (string) ($pageIndex + 1), $path, $svgPath]);
                $svg->mustRun();
                $grid = $this->svgGrid($svgPath);
            } finally {
                if (is_file($svgPath)) unlink($svgPath);
            }
            if (! $grid['period_edges']) continue;

            foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'] as $day) {
                $dayLine = collect($lines)->first(fn ($line) => $line['y'] > 100 && $line['x'] < 94 && strcasecmp(trim($line['text']), $day) === 0);
                if (! $dayLine) continue;
                $rowCenter = $dayLine['y'];
                $centers = collect($lines)->filter(fn ($line) => $line['y'] > 100 && $line['x'] < 94 && in_array(trim($line['text']), ['Senin','Selasa','Rabu','Kamis','Jumat'], true))->pluck('y')->sort()->values();
                $dayPosition = $centers->search(fn ($value) => abs($value - $rowCenter) < 1.5);
                $rowTop = $dayPosition === 0 ? $rowCenter - (($centers[1] - $centers[0]) / 2) : (($centers[$dayPosition - 1] + $rowCenter) / 2);
                $rowBottom = $dayPosition === $centers->count() - 1 ? $rowCenter + (($centers[$dayPosition] - $centers[$dayPosition - 1]) / 2) : (($rowCenter + $centers[$dayPosition + 1]) / 2);
                $edges = $grid['row_edges']($rowCenter);
                if (count($edges) < 2) continue;

                for ($edgeIndex = 0; $edgeIndex < count($edges) - 1; $edgeIndex++) {
                    $left = $edges[$edgeIndex]; $right = $edges[$edgeIndex + 1];
                    if ($right - $left < 15) continue;
                    $cellLines = array_values(array_filter($lines, fn ($line) =>
                        $line['y'] >= $rowTop
                        && $line['y'] <= $rowBottom
                        && $line['x'] > $left + 1
                        && $line['x'] < $right - 1
                        // The first timetable row's inferred top can overlap the
                        // period-number header. Those numbers are not lesson data.
                        && ! preg_match('/^\d+(?:\s+\d+)*$/', trim($line['text']))
                    ));
                    $cellText = trim(implode(' ', array_column($cellLines, 'text')));
                    if ($cellText === '') continue;
                    $nonRoomText = trim(collect($cellLines)->reject(fn ($line) => preg_match('/^(lab\.?|lap\b|r\s*\d)/i', trim($line['text'])))->pluck('text')->implode(' '));
                    $eventText = str_replace(' ', '', $this->normalize($nonRoomText));
                    if ($nonRoomText === '' || preg_match('/upaca|apel|pembia|harijumat/', $eventText) || str_contains($eventText, 'asctimetables')) continue;

                    $roomLine = collect($cellLines)->first(fn ($line) => preg_match('/^(lab\.?|lap\b|r\s*\d)/i', trim($line['text'])));
                    $teacherLines = collect($cellLines)->filter(fn ($line) => $line['y'] >= $rowBottom - 20 && ! preg_match('/^(lab\.?|lap\b|r\s*\d)/i', trim($line['text'])));
                    $lastTeacherLine = $teacherLines->sortByDesc('y')->first();
                    if ($lastTeacherLine && preg_match('/^(?:S\.?|M\.?|ST\b|M\.Pd\b|S\.Pd\b)/i', trim($lastTeacherLine['text']))) {
                        $precedingLine = collect($cellLines)
                            ->reject(fn ($line) => $line['y'] >= $lastTeacherLine['y'] || preg_match('/^(lab\.?|lap\b|r\s*\d)/i', trim($line['text'])))
                            ->sortByDesc('y')
                            ->first(fn ($line) => $lastTeacherLine['y'] - $line['y'] <= 15);
                        if ($precedingLine) $teacherLines = $teacherLines->push($precedingLine)->unique('text');
                    }
                    $teacherText = $teacherLines->pluck('text')->implode(' ');
                    $subjectLines = collect($cellLines)->reject(fn ($line) => $line['y'] >= $rowBottom - 20 || ($roomLine && $line['text'] === $roomLine['text']));
                    if ($teacherLines->isNotEmpty()) {
                        $teacherTextValues = $teacherLines->pluck('text')->all();
                        $subjectLines = $subjectLines->reject(fn ($line) => in_array($line['text'], $teacherTextValues, true));
                    }
                    $subjectText = trim($subjectLines->pluck('text')->implode(' '));
                    if ($subjectText === '') $subjectText = $nonRoomText;
                    if (preg_match('/^\d+$/', $subjectText)) continue;
                    $subjectId = $this->matchMaster($subjectText, $mapels, 'nama_mapel', 'id_mapel');
                    $teacherId = $this->matchMaster($teacherText, $gurus, 'nama_guru', 'id_guru');
                    if (! $teacherId) $teacherId = $this->matchMasterLines($teacherLines->all(), $gurus, 'nama_guru', 'id_guru');
                    if (! $teacherId) $teacherId = $this->matchMasterMentionedInText($nonRoomText, $gurus, 'nama_guru', 'id_guru');
                    if ($subjectId) {
                        $subjectText = $mapels->first(fn ($item) => $item->id_mapel == $subjectId)?->nama_mapel ?? $subjectText;
                    }
                    if ($teacherId) {
                        $teacherText = $gurus->first(fn ($item) => $item->id_guru == $teacherId)?->nama_guru ?? $teacherText;
                    }

                    $periodStart = $this->periodIndex($left, $grid['period_edges']);
                    $periodEnd = $this->periodIndex($right, $grid['period_edges']);
                    if ($periodStart === null || $periodEnd === null || $periodEnd <= $periodStart) continue;
                    $group = $day === 'Jumat' ? 'Jumat' : 'Senin-Kamis';
                    $startJam = $jamPels->first(fn ($jam) => $jam->klp_hari === $group && (int) $jam->jam_ke === $periodStart + 1);
                    $endJam = $jamPels->first(fn ($jam) => $jam->klp_hari === $group && (int) $jam->jam_ke === $periodEnd);
                    $roomText = $roomLine['text'] ?? '';
                    $result[] = [
                        'kelas_text' => $className, 'hari' => $day, 'guru_text' => $teacherText,
                        'mapel_text' => $subjectText, 'ruang_text' => $roomText,
                        'jam_text' => 'Jam '.($periodStart + 1).'–'.$periodEnd,
                        'id_kelas' => $kelasId, 'id_guru' => $teacherId, 'id_mapel' => $subjectId,
                        'id_jam_mulai' => $startJam?->id_jam, 'id_jam_selesai' => $endJam?->id_jam,
                        'semester' => $semester, 'tahun_ajaran' => $tahunAjaran,
                    ];
                }
            }
        }

        return $result;
    }

    private function bboxLines(DOMXPath $xpath, DOMElement $page): array
    {
        $result = [];
        foreach ($xpath->query('.//line', $page) ?: [] as $line) {
            if (! $line instanceof DOMElement) continue;
            $words = [];
            foreach ($line->getElementsByTagName('word') as $word) {
                $text = trim($word->textContent);
                if ($text !== '') $words[] = ['text' => $text, 'left' => (float) $word->getAttribute('xmin'), 'right' => (float) $word->getAttribute('xmax'), 'top' => (float) $word->getAttribute('ymin'), 'bottom' => (float) $word->getAttribute('ymax')];
            }
            $groups = [];
            foreach ($words as $word) {
                $last = array_key_last($groups);
                // Keep centered page headings intact; inside the timetable grid,
                // split adjacent cells even when aSc leaves only a narrow gap.
                $gapThreshold = $word['top'] < 70 ? 12 : 5;
                if ($last === null || $word['left'] - $groups[$last]['right'] > $gapThreshold) {
                    $groups[] = ['words' => [$word['text']], 'left' => $word['left'], 'right' => $word['right'], 'top' => $word['top'], 'bottom' => $word['bottom']];
                } else {
                    $groups[$last]['words'][] = $word['text'];
                    $groups[$last]['right'] = $word['right'];
                    $groups[$last]['top'] = min($groups[$last]['top'], $word['top']);
                    $groups[$last]['bottom'] = max($groups[$last]['bottom'], $word['bottom']);
                }
            }
            foreach ($groups as $group) {
                $result[] = ['text' => implode(' ', $group['words']), 'x' => ($group['left'] + $group['right']) / 2, 'y' => ($group['top'] + $group['bottom']) / 2];
            }
        }
        return $result;
    }

    private function svgGrid(string $path): array
    {
        $document = new DOMDocument();
        if (! @$document->load($path)) return ['period_edges' => [], 'row_edges' => fn () => []];
        $xpath = new DOMXPath($document);
        $segments = [];
        foreach ($xpath->query('//*[local-name()="path"][@stroke]') ?: [] as $pathNode) {
            if (! $pathNode instanceof DOMElement) continue;
            if (! preg_match('/M\s*(-?[\d.]+)[ ,]+(-?[\d.]+)\s*L\s*(-?[\d.]+)[ ,]+(-?[\d.]+)/', $pathNode->getAttribute('d'), $m)) continue;
            [$x1, $y1, $x2, $y2] = [(float) $m[1], (float) $m[2], (float) $m[3], (float) $m[4]];
            if (preg_match('/matrix\(([^)]+)\)/', $pathNode->getAttribute('transform'), $matrix)) {
                $parts = array_map('floatval', preg_split('/[ ,]+/', trim($matrix[1])));
                if (count($parts) === 6) {
                    [$a,$b,$c,$d,$e,$f] = $parts;
                    [$x1,$y1,$x2,$y2] = [$a*$x1+$c*$y1+$e,$b*$x1+$d*$y1+$f,$a*$x2+$c*$y2+$e,$b*$x2+$d*$y2+$f];
                }
            }
            if (abs($x1 - $x2) < 0.5 && $x1 > 80 && $x1 < 835) $segments[] = ['x' => ($x1 + $x2) / 2, 'top' => min($y1,$y2), 'bottom' => max($y1,$y2)];
        }
        $periodEdges = collect($segments)->filter(fn ($segment) => $segment['top'] < 90 && $segment['bottom'] > 80)->pluck('x')->unique(fn ($x) => round($x, 1))->sort()->values()->all();
        $rowEdges = static function (float $center) use ($segments): array {
            return collect($segments)->filter(fn ($segment) => $segment['top'] <= $center + 1 && $segment['bottom'] >= $center - 1)->pluck('x')->map(fn ($x) => round($x, 1))->unique()->sort()->values()->all();
        };
        return ['period_edges' => $periodEdges, 'row_edges' => $rowEdges];
    }

    private function periodIndex(float $x, array $periodEdges): ?int
    {
        $best = null; $distance = 4.0;
        foreach ($periodEdges as $index => $edge) {
            if (abs($edge - $x) < $distance) { $best = $index; $distance = abs($edge - $x); }
        }
        return $best;
    }

    private function matchMasterLines(array $lines, $items, string $label, string $key): ?int
    {
        foreach ($lines as $line) {
            $match = $this->matchMaster($line['text'], $items, $label, $key);
            if ($match) return $match;
        }
        return $this->matchMaster(implode(' ', array_column($lines, 'text')), $items, $label, $key);
    }

    private function matchMaster(string $value, $items, string $label, string $key): ?int
    {
        $needle = $this->normalize($value);
        if ($needle === '') return null;

        // PDF text can place a teacher's degree before the name or combine
        // adjacent text runs. Compare the name tokens without academic titles.
        $valueTokens = $this->personNameTokens($value);
        if ($valueTokens) {
            $exactTokenMatches = [];
            $containedTokenMatches = [];
            $singleTokenMatches = [];
            foreach ($items as $item) {
                $nameTokens = $this->personNameTokens($item->{$label});
                if (! $nameTokens) continue;
                if ($nameTokens === $valueTokens) $exactTokenMatches[] = $item->{$key};
                elseif (count($nameTokens) >= 2 && count(array_diff($nameTokens, $valueTokens)) === 0 && count(array_diff($valueTokens, $nameTokens)) <= 1) {
                    $containedTokenMatches[] = $item->{$key};
                }
                if (count($valueTokens) === 1 && strlen($valueTokens[0]) >= 3 && in_array($valueTokens[0], $nameTokens, true)) {
                    $singleTokenMatches[] = $item->{$key};
                }
            }

            if (count($exactTokenMatches) === 1) return $exactTokenMatches[0];
            if (count($containedTokenMatches) === 1) return $containedTokenMatches[0];
            if (count($singleTokenMatches) === 1) return $singleTokenMatches[0];
        }

        $bestId = null; $bestScore = 0;
        foreach ($items as $item) {
            $name = $this->normalize($item->{$label});
            if ($name === '') continue;
            if ($needle === $name || str_contains($needle, $name)) return $item->{$key};
            similar_text($name, $needle, $score);
            if ($score > $bestScore) { $bestScore = $score; $bestId = $item->{$key}; }
        }
        return $bestScore >= 78 ? $bestId : null;
    }

    private function personNameTokens(string $value): array
    {
        $value = Str::ascii(mb_strtolower($value));
        $value = preg_replace('/\b(?:s|m)\.?\s*(?:pd|kom|si|ag|sn|e|t|ds|psi|ss|st|par|tr)\.?i?\b/u', ' ', $value);
        $value = preg_replace('/\b(?:dra?|prof)\.?\b/u', ' ', $value);
        $tokens = preg_split('/[^a-z]+/u', $value, -1, PREG_SPLIT_NO_EMPTY) ?: [];
        $tokens = array_values(array_filter($tokens, fn ($token) => strlen($token) > 1));
        $tokens = array_values(array_unique($tokens));
        sort($tokens);

        return $tokens;
    }

    private function matchMasterMentionedInText(string $value, $items, string $label, string $key): ?int
    {
        $sourceTokens = $this->personNameTokens($value);
        if (count($sourceTokens) < 2) return null;

        $matches = [];
        foreach ($items as $item) {
            $nameTokens = $this->personNameTokens($item->{$label});
            $allNameTokensPresent = count($nameTokens) >= 2;
            foreach ($nameTokens as $nameToken) {
                $tokenFound = collect($sourceTokens)->contains(fn ($sourceToken) =>
                    $sourceToken === $nameToken
                    || (strlen($nameToken) >= 7 && levenshtein($sourceToken, $nameToken) <= 1)
                );
                if (! $tokenFound) {
                    $allNameTokensPresent = false;
                    break;
                }
            }

            if ($allNameTokensPresent) {
                $matches[] = $item->{$key};
            }
        }

        return count($matches) === 1 ? $matches[0] : null;
    }

    private function readSheet(array $rows): array
    {
        $headerIndex = null; $map = [];
        foreach (array_slice($rows, 0, 15, true) as $i => $row) {
            foreach ($row as $column => $cell) {
                $name = $this->normalize($cell);
                foreach (['kelas','hari','jam mulai','jam selesai','jam','guru','mapel','pelajaran','mata pelajaran','semester','tahun ajaran','ruang'] as $field) {
                    if ($name !== '' && ($field === 'jam' ? (str_contains($name, 'jam') && ! str_contains($name, 'mulai') && ! str_contains($name, 'selesai')) : (str_contains($name, $this->normalize($field)) || ($field === 'mapel' && str_contains($name, 'mata pelajaran'))))) $map[$field] = $column;
                }
            }
            if (isset($map['kelas'], $map['hari']) && (isset($map['mapel']) || isset($map['pelajaran'])) && isset($map['guru'])) { $headerIndex = $i; break; }
            $map = [];
        }
        if ($headerIndex === null) return [];
        $get = fn ($row, $key) => $row[$map[$key] ?? $map['mapel'] ?? $map['pelajaran'] ?? ''] ?? null;
        $result = [];
        foreach (array_slice($rows, $headerIndex + 1, null, true) as $row) {
            $values = array_map(fn ($v) => trim((string) $v), $row);
            if (count(array_filter($values)) < 2) continue;
            $result[] = [
                'kelas_text' => (string) $get($row, 'kelas'), 'hari' => $this->day((string) $get($row, 'hari')),
                'guru_text' => (string) $get($row, 'guru'), 'mapel_text' => (string) $get($row, 'mapel'),
                'jam_text' => trim((string) ($get($row, 'jam') ?? '').' '.(string) ($get($row, 'jam mulai') ?? '').' - '.(string) ($get($row, 'jam selesai') ?? '')), 'id_kelas' => $this->matchId((string) $get($row, 'kelas'), Kelas::all(), 'nama_kelas', 'id_kelas'),
                'id_guru' => $this->matchId((string) $get($row, 'guru'), Guru::all(), 'nama_guru', 'id_guru'),
                'id_mapel' => $this->matchId((string) $get($row, 'mapel'), Mapel::all(), 'nama_mapel', 'id_mapel'),
                'id_jam_mulai' => null, 'id_jam_selesai' => null,
                'semester' => 'Ganjil', 'tahun_ajaran' => '',
            ];
            $this->matchHour($result[array_key_last($result)]);
        }
        return $result;
    }

    private function matchHour(array &$row): void
    {
        $text = $this->normalize($row['jam_text'].' '.$row['jam_text']);
        if (preg_match('/(\d{1,2}[:.]\d{2}).*(\d{1,2}[:.]\d{2})/', $text, $m)) {
            $start = str_replace('.', ':', $m[1]); $end = str_replace('.', ':', $m[2]);
            $a = JamPel::where('jam_mulai', 'like', $start.'%')->first(); $b = JamPel::where('jam_selesai', 'like', $end.'%')->first();
            $row['id_jam_mulai'] = $a?->id_jam; $row['id_jam_selesai'] = $b?->id_jam; return;
        }
        if (preg_match('/(\d{1,2}[:.]\d{2})\s*(?:-|sampai|sd)\s*(\d{1,2}[:.]\d{2})/', $text, $m)) {
            $start = str_replace('.', ':', $m[1]); $end = str_replace('.', ':', $m[2]);
            $a = JamPel::where('jam_mulai', 'like', $start.'%')->first(); $b = JamPel::where('jam_selesai', 'like', $end.'%')->first();
            $row['id_jam_mulai'] = $a?->id_jam; $row['id_jam_selesai'] = $b?->id_jam; return;
        }
        if (preg_match('/(?:jam\s*)?(\d{1,2})/', $text, $m)) {
            $jam = JamPel::where('jam_ke', (int) $m[1])->first(); $row['id_jam_mulai'] = $jam?->id_jam; $row['id_jam_selesai'] = $jam?->id_jam;
        }
    }

    private function matchId(string $value, $items, string $label, string $key): ?int
    {
        $needle = $this->normalize($value); if ($needle === '') return null;
        foreach ($items as $item) if ($this->normalize($item->{$label}) === $needle) return $item->{$key};
        return null;
    }

    private function day(string $value): string
    {
        foreach (['Senin','Selasa','Rabu','Kamis','Jumat'] as $day) if (str_contains($this->normalize($value), $this->normalize($day))) return $day;
        return '';
    }

    private function normalize(?string $value): string
    {
        return Str::of((string) $value)->ascii()->lower()->replaceMatches('/[^a-z0-9:.-]+/', ' ')->squish()->toString();
    }

    private function importCacheKey(string $token): string
    {
        return 'jadwal_import.'.$token;
    }

    private function overlaps(JamPel $a, JamPel $b, ?JamPel $c, ?JamPel $d): bool
    {
        return $c && $d && $a->jam_mulai < $d->jam_selesai && $b->jam_selesai > $c->jam_mulai;
    }
}
