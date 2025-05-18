<?php

namespace QuerySpy;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class QuerySpyServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot(): void
    {
        Log::info('[QuerySpy] ServiceProvider booted!');
    }

    public function register()
    {
        //
    }
}
