<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['middleware' => ['internal.api.auth']], function () {
    Route::prefix('/clientes')->group(function () {
        Route::get('/', function () {
            return 'clientes';
        })->name('clientes');
    });
});
