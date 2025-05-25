<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/test-slow', function () {
    DB::select('SELECT pg_sleep(1)');
    return 'done';
});

