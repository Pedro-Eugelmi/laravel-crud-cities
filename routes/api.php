<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CityController;
use App\Http\Controllers\UfController;

// Rotas para as cidades
Route::get('/cities', [CityController::class, 'index']);
Route::post('/cities', [CityController::class, 'store']);
Route::get('/cities/{city}', [CityController::class, 'show']);
Route::put('/cities/{city}', [CityController::class, 'update']);
Route::delete('/cities/{city}', [CityController::class, 'destroy']);

// Rotas para as UFs
Route::get('/ufs', [UfController::class, 'index']);
Route::post('/ufs', [UfController::class, 'store']);
Route::get('/ufs/{uf}', [UfController::class, 'show']);
Route::put('/ufs/{uf}', [UfController::class, 'update']);
Route::delete('/ufs/{uf}', [UfController::class, 'destroy']);