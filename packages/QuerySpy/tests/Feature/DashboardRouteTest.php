<?php

namespace QuerySpy\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use QuerySpy\Models\QuerySpyEntry;
use Tests\TestCase;

class DashboardRouteTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_loads_the_queryspy_dashboard_page()
    {

        QuerySpyEntry::create([
            'sql' => 'SELECT * FROM users ORDER BY name',
            'bindings' => [],
            'time_ms' => 1234.56,
            'source_file' => 'routes/web.php',
            'source_line' => 12,
        ]);

        $response = $this->get('/queryspy');

        $response->assertStatus(200);
        $response->assertSee('QuerySpy – Slow Queries');
        $response->assertSee('SELECT * FROM users');
        $response->assertSee('ORDER BY');
    }
}
