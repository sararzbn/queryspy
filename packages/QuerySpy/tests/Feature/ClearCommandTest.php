<?php

namespace QuerySpy\Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class ClearCommandTest extends TestCase
{
    /** @test */
    /** @test */
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

}
