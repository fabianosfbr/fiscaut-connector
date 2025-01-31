<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['middleware' => ['internal.api.auth']], function () {
    Route::prefix('/clientes')->group(function () {
        Route::post('/', [App\Http\Controllers\ClienteController::class, 'getClientes'])->name('clientes');
    });
    Route::prefix('/empresas')->group(function () {
        Route::post('/', [App\Http\Controllers\EmpresaController::class, 'getEmpresa'])->name('getEmpresa');
    });
});
