<?php

namespace QuerySpy;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use QuerySpy\Console\AnalyzeCommand;
use QuerySpy\Console\ExportCommand;

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
                $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 20);

                $source = collect($backtrace)->first(function ($trace) {
                    if (!isset($trace['file'])) return false;

                    $file = $trace['file'];

                    return str_starts_with($file, base_path('routes')) ||
                        str_starts_with($file, base_path('app')) ||
                        str_starts_with($file, base_path('resources'));
                });


                Log::channel('queryspy')->info('Slow query detected', [
                    'sql' => $query->sql,
                    'bindings' => $query->bindings,
                    'time_ms' => $query->time,
                    'source' => $source ? [
                        'file' => str_replace(base_path() . '/', '', $source['file']),
                        'line' => $source['line'] ?? null,
                    ] : null,
                ]);
            }
        });


        if ($this->app->runningInConsole()) {
            $this->commands([
                AnalyzeCommand::class,
                ExportCommand::class,
            ]);
        }

        if (! $this->app->routesAreCached()) {
            Route::middleware('web')
                ->prefix('queryspy')
                ->group(__DIR__.'/../routes/web.php');
        }

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'queryspy');

    }

    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/queryspy.php', 'queryspy'
        );

    }
}
