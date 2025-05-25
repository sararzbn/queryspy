<?php

namespace QuerySpy\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

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
        $logPath = storage_path('logs/queryspy.log');
        $exportDir = storage_path('queryspy');
        $filename = $exportDir . '/queryspy_export.' . $format;

        if (!file_exists($logPath)) {
            $this->error('Log file not found: ' . $logPath);
            return;
        }

        File::ensureDirectoryExists($exportDir);

        $lines = file($logPath);
        $entries = [];

        foreach ($lines as $line) {
            if (!str_contains($line, 'Slow query detected')) continue;

            $jsonStart = strpos($line, '{');
            if ($jsonStart === false) continue;

            $json = substr($line, $jsonStart);
            $data = json_decode($json, true);
            if (!$data) continue;

            $entries[] = [
                'time_ms' => round($data['time_ms'], 2),
                'sql' => $data['sql'],
                'source' => $data['source']['file'] ?? 'unknown',
                'line' => $data['source']['line'] ?? '',
            ];
        }

        if ($format === 'json') {
            file_put_contents($filename, json_encode($entries, JSON_PRETTY_PRINT));
            $this->info("Exported to JSON: $filename");
        } elseif ($format === 'csv') {
            $csv = fopen($filename, 'w');
            fputcsv($csv, ['time_ms', 'sql', 'source', 'line']);
            foreach ($entries as $entry) {
                fputcsv($csv, $entry);
            }
            fclose($csv);
            $this->info("Exported to CSV: $filename");
        } else {
            $this->error("Unsupported format: $format (use 'csv' or 'json')");
        }
    }
}
