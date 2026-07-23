<?php

return [
    /*
     * Master switch. Set QUERYSPY_ENABLED=false in your .env to disable
     * query capturing entirely, regardless of environment.
     */
    'enabled' => env('QUERYSPY_ENABLED', true),

    /*
     * Only capture queries in these environments. An empty array means
     * "capture in all environments". Keeping this restricted to local/
     * staging prevents QuerySpy from writing to your production database.
     */
    'environments' => ['local', 'staging'],

    /*
     * Queries slower than this many milliseconds are recorded.
     */
    'threshold' => env('QUERYSPY_THRESHOLD', 300),
];
