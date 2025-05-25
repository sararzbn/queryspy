<?php

namespace QuerySpy;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use QuerySpy\Console\AnalyzeCommand;

class QuerySpyServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/queryspy.php' => config_path('queryspy.php'),
        ], 'queryspy-config');

        DB::listen(function ($query) {
            $threshold = config('queryspy.threshold', 300);

            if ($query->time > $threshold) {
                Log::channel('queryspy')->info('Slow query detected', [
                    'sql' => $query->sql,
                    'bindings' => $query->bindings,
                    'time_ms' => $query->time,
                    'source' => collect(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS))
                        ->filter(fn ($trace) => isset($trace['file']) && str_contains($trace['file'], base_path('app')))
                        ->first(),
                ]);
            }
        });

        if ($this->app->runningInConsole()) {
            $this->commands([
                AnalyzeCommand::class,
            ]);
        }


    }

    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/queryspy.php', 'queryspy'
        );

    }
}
