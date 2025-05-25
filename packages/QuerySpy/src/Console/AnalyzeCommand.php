<?php

namespace QuerySpy\Console;

use Illuminate\Console\Command;

class AnalyzeCommand extends Command
{
    protected $signature = 'queryspy:analyze';
    protected $description = 'Analyze and display slow queries from queryspy.log';

    public function handle(): void
    {
        $logPath = storage_path('logs/queryspy.log');

        if (!file_exists($logPath)) {
            $this->error('Log file not found: ' . $logPath);
            return;
        }

        $lines = file($logPath);

        foreach ($lines as $line) {
            if (!str_contains($line, 'Slow query detected')) continue;

            $jsonStart = strpos($line, '{');
            if ($jsonStart === false) continue;

            $json = substr($line, $jsonStart);
            $data = json_decode($json, true);

            if (!is_array($data)) continue;

            $this->line('');
            $this->warn("[!] Slow Query (" . round($data['time_ms'], 2) . " ms)");
            $this->info("SQL: " . $data['sql']);
            if (!empty($data['source']['file'])) {
                $this->line("Source: {$data['source']['file']}:{$data['source']['line']}");
            } else {
                $this->line("Source: unknown");
            }
        }
    }
}
