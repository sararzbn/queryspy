<?php

namespace QuerySpy\Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use QuerySpy\Models\QuerySpyEntry;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExportCommandTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function it_exports_queries_to_csv()
    {

        QuerySpyEntry::create([
            'sql' => 'SELECT * FROM test',
            'bindings' => [],
            'time_ms' => 1001.2,
            'source_file' => 'routes/web.php',
            'source_line' => 10,
        ]);

        Artisan::call('queryspy:export', ['--format' => 'csv']);

        $outputPath = storage_path('queryspy/queryspy_export.csv');

        $this->assertFileExists($outputPath);
        $contents = File::get($outputPath);
        $this->assertStringContainsString('SELECT * FROM test', $contents);
    }
}
