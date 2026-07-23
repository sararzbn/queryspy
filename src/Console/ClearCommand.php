<?php

namespace QuerySpy\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use QuerySpy\Models\QuerySpyEntry;

class ClearCommand extends Command
{
    protected $signature = 'queryspy:clear';
    protected $description = 'Clear all recorded slow queries (database table and log file)';

    /**
     * @return void
     */
    public function handle(): void
    {
        // Clear the database table — this is what the dashboard and export read from.
        if (Schema::hasTable('query_spy_entries')) {
            $count = QuerySpyEntry::count();
            QuerySpyEntry::query()->delete();
            $this->info("Cleared {$count} slow query entries from the database.");
        } else {
            $this->warn('The query_spy_entries table does not exist.');
        }

        // Also clear the log file if it exists.
        $logPath = storage_path('logs/queryspy.log');

        if (file_exists($logPath)) {
            file_put_contents($logPath, '');
            $this->line('QuerySpy log file cleared.');
        }
    }
}
