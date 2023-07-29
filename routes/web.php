<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', fn () => view('pages.index'))->name('index');
Route::get('/history', fn () => view('pages.history.index'))->name('history');
Route::get('/settings', fn () => view('pages.settings.index'))->name('settings');
