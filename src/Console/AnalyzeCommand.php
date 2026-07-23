<?php

namespace QuerySpy\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use QuerySpy\Models\QuerySpyEntry;

class AnalyzeCommand extends Command
{
    protected $signature = 'queryspy:analyze';
    protected $description = 'Analyze and display recorded slow queries';

    public function handle(): void
    {
        if (!Schema::hasTable('query_spy_entries')) {
            $this->warn('The query_spy_entries table does not exist. Run "php artisan migrate" first.');
            return;
        }

        // Slowest queries first — the most impactful ones to look at.
        $entries = QuerySpyEntry::orderByDesc('time_ms')->get();

        if ($entries->isEmpty()) {
            $this->info('No slow queries recorded.');
            return;
        }

        foreach ($entries as $entry) {
            $this->line('');
            $this->warn('[!] Slow Query (' . round($entry->time_ms, 2) . ' ms)');
            $this->info('SQL: ' . $entry->sql);

            if ($entry->source_file) {
                $this->line("Source: {$entry->source_file}:{$entry->source_line}");
            } else {
                $this->line('Source: unknown');
            }
        }

        $this->line('');
        $this->info('Total: ' . $entries->count() . ' slow queries.');
    }
}
