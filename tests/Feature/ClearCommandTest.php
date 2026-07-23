<?php

namespace QuerySpy\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use QuerySpy\Models\QuerySpyEntry;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ClearCommandTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_clears_the_log_file()
    {
        $logPath = storage_path('logs/queryspy.log');

        File::ensureDirectoryExists(dirname($logPath));
        File::put($logPath, 'some slow query log');

        $this->assertFileExists($logPath);
        $this->assertNotEmpty(File::get($logPath));

        Artisan::call('queryspy:clear');

        $this->assertEquals('', File::get($logPath));
    }

    #[Test]
    public function it_clears_the_database_entries()
    {
        QuerySpyEntry::create([
            'sql' => 'SELECT * FROM users',
            'bindings' => [],
            'time_ms' => 1234.56,
            'source_file' => 'routes/web.php',
            'source_line' => 12,
        ]);

        $this->assertDatabaseCount('query_spy_entries', 1);

        Artisan::call('queryspy:clear');

        $this->assertDatabaseCount('query_spy_entries', 0);
    }

}
