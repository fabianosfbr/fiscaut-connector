<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['middleware' => ['internal.api.auth']], function () {
    Route::prefix('/clientes')->group(function () {
        Route::post('/', [App\Http\Controllers\ClienteController::class, 'getClientes'])
            ->name('clientes');
    });
    Route::prefix('/fornecedores')->group(function () {
        Route::post('/', [App\Http\Controllers\FornecedorController::class, 'getFornecedores'])
            ->name('clientes');
    });

    Route::prefix('/planos')->group(function () {
        Route::post('/', [App\Http\Controllers\PlanoDeContaController::class, 'getPlanoDeContas'])
        ->name('getPlanoDeContas');
    });
});
