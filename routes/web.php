<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;

Route::get('/', [EventController::class, 'index']);

Route::get('/create-form', [EventController::class, 'createForm']);
Route::post('/store', [EventController::class, 'store']);

Route::get('/edit/{id}', [EventController::class, 'edit']);
Route::post('/update/{id}', [EventController::class, 'update']);

Route::get('/delete/{id}', [EventController::class, 'delete']);

Route::get('/daily', [EventController::class, 'daily']);
Route::get('/weekly', [EventController::class, 'weekly']);
Route::get('/every-2-days', [EventController::class, 'everyTwoDays']);
Route::get('/today', [EventController::class, 'today']);
Route::get('/between', [EventController::class, 'between']);