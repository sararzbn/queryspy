<?php

use Illuminate\Support\Facades\Route;
use QuerySpy\Http\Controllers\DashboardController;

Route::get('/', [DashboardController::class, 'index']);
