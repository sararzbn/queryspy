<?php

namespace QuerySpy\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use QuerySpy\Models\QuerySpyEntry;

class PruneCommand extends Command
{
    protected $signature = 'queryspy:prune {--days= : Delete entries older than this many days}';
    protected $description = 'Delete slow query entries older than the retention period';

    public function handle(): void
    {
        if (!Schema::hasTable('query_spy_entries')) {
            $this->warn('The query_spy_entries table does not exist.');
            return;
        }

        $days = (int) ($this->option('days') ?? config('queryspy.retention_days', 7));

        if ($days < 1) {
            $this->error('The number of days must be at least 1.');
            return;
        }

        $cutoff = now()->subDays($days);

        $deleted = QuerySpyEntry::where('created_at', '<', $cutoff)->delete();

        $this->info("Pruned {$deleted} slow query entries older than {$days} day(s).");
    }
}
