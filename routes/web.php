<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/test-slow', function () {
    DB::select('SELECT pg_sleep(1)');
    return 'done';
});

Route::get('/test-smart', function () {
    $sql = 'SELECT * FROM posts JOIN categories ON posts.cat_id = categories.id ORDER BY posts.created_at';
    Log::channel('queryspy')->info('Slow query detected', [
        'sql' => $sql,
        'bindings' => [],
        'time_ms' => 1200,
        'source' => ['file' => 'routes/web.php', 'line' => 20],
    ]);
    return 'done';
});

