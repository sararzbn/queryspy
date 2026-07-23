<?php

namespace QuerySpy\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use QuerySpy\Models\QuerySpyEntry;
use Tests\TestCase;

class AnalyzeCommandTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_displays_recorded_slow_queries_from_the_database()
    {
        QuerySpyEntry::create([
            'sql' => 'SELECT * FROM users',
            'bindings' => [],
            'time_ms' => 1234.56,
            'source_file' => 'routes/web.php',
            'source_line' => 12,
        ]);

        $this->artisan('queryspy:analyze')
            ->expectsOutputToContain('SELECT * FROM users')
            ->expectsOutputToContain('routes/web.php:12')
            ->assertExitCode(0);
    }

    #[Test]
    public function it_reports_when_there_are_no_entries()
    {
        $this->artisan('queryspy:analyze')
            ->expectsOutputToContain('No slow queries recorded.')
            ->assertExitCode(0);
    }
}
