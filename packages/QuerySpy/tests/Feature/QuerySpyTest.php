<?php

namespace QuerySpy\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use QuerySpy\Models\QuerySpyEntry;
use Tests\TestCase;

class QuerySpyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_store_a_slow_query_into_the_database()
    {
        QuerySpyEntry::create([
            'sql' => 'SELECT * FROM users',
            'bindings' => [],
            'time_ms' => 1234.56,
            'source_file' => 'routes/web.php',
            'source_line' => 12,
        ]);

        $this->assertDatabaseCount('query_spy_entries', 1);
    }
}
