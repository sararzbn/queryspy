<?php

namespace QuerySpy\Tests\Feature;

use QuerySpy\Support\helpers;
use Tests\TestCase;

class SuggestionTest extends TestCase
{
    /** @test */
    public function it_detects_common_slow_query_patterns()
    {
        $sql = 'SELECT * FROM posts JOIN categories ON posts.cat_id = categories.id JOIN users ON users.id = posts.user_id ORDER BY posts.created_at';

        $suggestion = helpers::getSuggestionForQuery($sql);

        $this->assertStringContainsString('Avoid SELECT *', $suggestion);
        $this->assertStringContainsString('Multiple JOINs detected', $suggestion);
        $this->assertStringContainsString('ORDER BY', $suggestion);
    }


    /** @test */
    public function it_returns_positive_message_for_optimized_queries()
    {
        $sql = 'SELECT id, name FROM users WHERE active = true LIMIT 10';

        $suggestion = helpers::getSuggestionForQuery($sql);

        $this->assertEquals('✅ Looks fine', $suggestion);
    }
}
