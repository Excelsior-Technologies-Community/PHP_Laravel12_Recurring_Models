<?php

use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Main routes
Route::get('/', [EventController::class, 'index']);
Route::get('/create-form', [EventController::class, 'createForm']);
Route::post('/store', [EventController::class, 'store']);
Route::get('/edit/{id}', [EventController::class, 'edit']);
Route::post('/update/{id}', [EventController::class, 'update']);
Route::get('/delete/{id}', [EventController::class, 'delete']);
Route::get('/complete/{id}', [EventController::class, 'complete']);

// Filter routes
Route::get('/daily', [EventController::class, 'daily']);
Route::get('/weekly', [EventController::class, 'weekly']);
Route::get('/every-2-days', [EventController::class, 'everyTwoDays']);
Route::get('/today', [EventController::class, 'today']);
Route::get('/between', [EventController::class, 'between']);

// Advanced features
Route::get('/search', [EventController::class, 'search'])->name('events.search');
Route::get('/date-range', [EventController::class, 'dateRange'])->name('events.daterange');
Route::get('/export-csv', [EventController::class, 'exportCsv'])->name('events.export');
Route::get('/duplicate/{id}', [EventController::class, 'duplicate'])->name('events.duplicate');
Route::get('/toggle-important/{id}', [EventController::class, 'toggleImportant'])->name('events.toggle-important');
Route::get('/summary', [EventController::class, 'summary'])->name('events.summary');
Route::post('/quick-add', [EventController::class, 'quickAdd'])->name('events.quick-add');