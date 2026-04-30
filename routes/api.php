<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\EfficiencyController;
use App\Http\Controllers\ObserverController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\LineController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SmvController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(ProductController::class)->prefix('products')->group(function (){
 Route::get('/', 'index');
 Route::post('/add', 'store');
});

Route::controller(EfficiencyController::class)->prefix('efficiency')->group(function (){
    Route::post('/add', 'store');
});

Route::controller(ObserverController::class)->prefix('observer')->group(function (){
    Route::get('/', 'index');
    Route::post('/add', 'store');
    
});

Route::controller(UnitController::class)->prefix('unit')->group(function (){
    Route::get('/', 'index');
    Route::post('/add', 'store');
    
});
Route::controller(LineController::class)->prefix('lines')->group(function (){
    Route::get('/', 'index');
    
});
Route::controller(ReportController::class)->prefix('report')->group(function (){
    Route::get('/monthly', 'getMonthlyReport'); 
});

Route::controller(SmvController::class)->prefix('smv')->group(function (){
    Route::post('/add', 'store'); 
});


