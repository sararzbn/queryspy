<?php

namespace QuerySpy\Console;

use Illuminate\Console\Command;

class ClearCommand extends Command
{
    protected $signature = 'queryspy:clear';
    protected $description = 'Clear the slow query log file';

    /**
     * @return void
     */
    public function handle(): void
    {
        $logPath = storage_path('logs/queryspy.log');

        if (!file_exists($logPath)) {
            $this->info('Log file does not exist. Nothing to clear.');
            return;
        }

        file_put_contents($logPath, '');
        $this->info('QuerySpy log cleared successfully.');
    }
}
