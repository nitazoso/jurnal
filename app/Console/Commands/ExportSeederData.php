<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ExportSeederData extends Command
{
    protected $signature = 'db:export-seeders';

    protected $description = 'Export data database menjadi Seeder Laravel';

    public function handle()
    {
        $tables = [
            'gurus' => 'GuruSeeder.php',
            'kelases' => 'KelasSeeder.php',
            'siswas' => 'SiswaSeeder.php',
            'mapels' => 'MapelSeeder.php',
            'jam_pels' => 'JamPelSeeder.php',
            'jadwals' => 'JadwalSeeder.php',
            'piket_jadwals' => 'PiketJadwalSeeder.php',
        ];

        foreach ($tables as $table => $filename) {

            if (!DB::getSchemaBuilder()->hasTable($table)) {
                $this->warn("Tabel {$table} tidak ditemukan.");
                continue;
            }

            $rows = DB::table($table)->get();

            $path = database_path("seeders/{$filename}");

            $className = pathinfo($filename, PATHINFO_FILENAME);

            $content = "<?php\n\n";
            $content .= "namespace Database\\Seeders;\n\n";
            $content .= "use Illuminate\\Database\\Seeder;\n";
            $content .= "use Illuminate\\Support\\Facades\\DB;\n\n";
            $content .= "class {$className} extends Seeder\n";
            $content .= "{\n";
            $content .= "    public function run(): void\n";
            $content .= "    {\n";

            if ($rows->isEmpty()) {

                $content .= "        // Tidak ada data.\n";

            } else {

                $data = $rows->map(function ($row) {
                    return (array) $row;
                })->toArray();

                $content .= "        DB::table('{$table}')->insert(\n";
                $content .= var_export($data, true);
                $content .= "\n        );\n";
            }

            $content .= "    }\n";
            $content .= "}\n";

            file_put_contents($path, $content);

            $this->info("{$filename} berhasil dibuat: {$rows->count()} data.");
        }

        $this->info('');
        $this->info('Export selesai.');
    }
}