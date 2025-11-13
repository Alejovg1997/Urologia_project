<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Web routes for urology management
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
