<?php

use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;

Route::get('/', [EventController::class, 'index'])->name('events.index');
Route::get('/calendar', [EventController::class, 'calendar'])->name('events.calendar');
Route::get('/calendar-data', [EventController::class, 'getCalendarEvents'])->name('events.calendar.data');
Route::post('/update-event-date', [EventController::class, 'updateEventDate'])->name('events.update-date');

Route::get('/create-form', [EventController::class, 'createForm'])->name('events.create');
Route::post('/store', [EventController::class, 'store'])->name('events.store');
Route::get('/edit/{id}', [EventController::class, 'edit'])->name('events.edit');
Route::post('/update/{id}', [EventController::class, 'update'])->name('events.update');
Route::get('/delete/{id}', [EventController::class, 'delete'])->name('events.delete');
Route::get('/complete/{id}', [EventController::class, 'complete'])->name('events.complete');
Route::post('/update-status/{id}', [EventController::class, 'updateStatus'])->name('events.update-status');

Route::get('/daily', [EventController::class, 'daily'])->name('events.daily');
Route::get('/weekly', [EventController::class, 'weekly'])->name('events.weekly');
Route::get('/every-2-days', [EventController::class, 'everyTwoDays'])->name('events.every-2-days');
Route::get('/today', [EventController::class, 'today'])->name('events.today');
Route::get('/between', [EventController::class, 'between'])->name('events.between');

Route::get('/search', [EventController::class, 'search'])->name('events.search');
Route::get('/date-range', [EventController::class, 'dateRange'])->name('events.daterange');
Route::get('/export-csv', [EventController::class, 'exportCsv'])->name('events.export');
Route::get('/duplicate/{id}', [EventController::class, 'duplicate'])->name('events.duplicate');
Route::get('/toggle-important/{id}', [EventController::class, 'toggleImportant'])->name('events.toggle-important');
Route::get('/summary', [EventController::class, 'summary'])->name('events.summary');
Route::post('/quick-add', [EventController::class, 'quickAdd'])->name('events.quick-add');