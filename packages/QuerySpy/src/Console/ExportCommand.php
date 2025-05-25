<?php

namespace QuerySpy\Console;

use Illuminate\Console\Command;
use QuerySpy\Models\QuerySpyEntry;

class ExportCommand extends Command
{
    protected $signature = 'queryspy:export {--format=csv : Export format (csv or json)}';
    protected $description = 'Export slow queries from log file to CSV or JSON';

    /**
     * @return void
     */
    public function handle(): void
    {
        $format = $this->option('format');

        $entries = QuerySpyEntry::orderByDesc('created_at')->get();

        if ($entries->isEmpty()) {
            $this->warn('No entries to export.');
            return;
        }

        $exportDir = storage_path('queryspy');
        if (!is_dir($exportDir)) {
            mkdir($exportDir, 0755, true);
        }

        if ($format === 'json') {
            $path = $exportDir . '/queryspy_export.json';
            file_put_contents($path, $entries->toJson(JSON_PRETTY_PRINT));
        } else {
            $path = $exportDir . '/queryspy_export.csv';
            $fp = fopen($path, 'w');
            fputcsv($fp, ['time_ms', 'sql', 'source', 'line']);
            foreach ($entries as $entry) {
                fputcsv($fp, [
                    round($entry->time_ms, 2),
                    $entry->sql,
                    $entry->source_file ?? 'unknown',
                    $entry->source_line ?? '',
                ]);
            }
            fclose($fp);
        }

        $this->info("Exported to " . strtoupper($format) . ": {$path}");
    }
}
