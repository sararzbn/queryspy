<?php

namespace QuerySpy\Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use QuerySpy\Support\QuerySpy;
use Tests\TestCase;

class EnabledConfigTest extends TestCase
{
    #[Test]
    public function it_is_disabled_when_the_master_switch_is_off()
    {
        config(['queryspy.enabled' => false]);

        $this->assertFalse(QuerySpy::isEnabled());
    }

    #[Test]
    public function it_is_disabled_in_an_environment_that_is_not_allowed()
    {
        config([
            'queryspy.enabled' => true,
            'queryspy.environments' => ['production'],
        ]);

        // The test environment is "testing", which is not in the allowed list.
        $this->assertFalse(QuerySpy::isEnabled());
    }

    #[Test]
    public function it_is_enabled_in_an_allowed_environment()
    {
        config([
            'queryspy.enabled' => true,
            'queryspy.environments' => ['testing'],
        ]);

        $this->assertTrue(QuerySpy::isEnabled());
    }

    #[Test]
    public function an_empty_environment_list_means_all_environments()
    {
        config([
            'queryspy.enabled' => true,
            'queryspy.environments' => [],
        ]);

        $this->assertTrue(QuerySpy::isEnabled());
    }
}
