<?php

namespace QuerySpy\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use PHPUnit\Framework\Attributes\Test;
use QuerySpy\Models\QuerySpyEntry;
use Tests\TestCase;

class PruneCommandTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_deletes_entries_older_than_the_retention_period()
    {
        config(['queryspy.retention_days' => 7]);

        // Old entry — should be pruned.
        $old = QuerySpyEntry::create([
            'sql' => 'SELECT * FROM old',
            'bindings' => [],
            'time_ms' => 500,
        ]);
        $old->forceFill(['created_at' => now()->subDays(10)])->save();

        // Recent entry — should be kept.
        QuerySpyEntry::create([
            'sql' => 'SELECT * FROM recent',
            'bindings' => [],
            'time_ms' => 500,
        ]);

        Artisan::call('queryspy:prune');

        $this->assertDatabaseMissing('query_spy_entries', ['sql' => 'SELECT * FROM old']);
        $this->assertDatabaseHas('query_spy_entries', ['sql' => 'SELECT * FROM recent']);
    }

    #[Test]
    public function it_respects_the_days_option()
    {
        // Entry from 3 days ago.
        $entry = QuerySpyEntry::create([
            'sql' => 'SELECT * FROM users',
            'bindings' => [],
            'time_ms' => 500,
        ]);
        $entry->forceFill(['created_at' => now()->subDays(3)])->save();

        // --days=2 should prune the 3-day-old entry.
        Artisan::call('queryspy:prune', ['--days' => 2]);

        $this->assertDatabaseCount('query_spy_entries', 0);
    }
}
