<?php

use App\Http\Controllers\FiliadoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CongregacaoController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Filiados
    Route::get('/filiados',                [FiliadoController::class, 'index'])->name('filiados.index');
    Route::get('/filiados/create',         [FiliadoController::class, 'create'])->name('filiados.create');
    Route::post('/filiados',               [FiliadoController::class, 'store'])->name('filiados.store');
    Route::get('/filiados/{filiado}',      [FiliadoController::class, 'show'])->name('filiados.show');
    Route::get('/filiados/{filiado}/edit', [FiliadoController::class, 'edit'])->name('filiados.edit');
    Route::put('/filiados/{filiado}',      [FiliadoController::class, 'update'])->name('filiados.update');
    Route::delete('/filiados/{filiado}',   [FiliadoController::class, 'destroy'])->name('filiados.destroy');

    // Congregações (apenas admin)
    Route::get('/congregacoes',                    [CongregacaoController::class, 'index'])->name('congregacoes.index');
    Route::get('/congregacoes/create',             [CongregacaoController::class, 'create'])->name('congregacoes.create');
    Route::post('/congregacoes',                   [CongregacaoController::class, 'store'])->name('congregacoes.store');
    Route::get('/congregacoes/{congregacao}',      [CongregacaoController::class, 'show'])->name('congregacoes.show');
    Route::get('/congregacoes/{congregacao}/edit', [CongregacaoController::class, 'edit'])->name('congregacoes.edit');
    Route::put('/congregacoes/{congregacao}',      [CongregacaoController::class, 'update'])->name('congregacoes.update');
    Route::delete('/congregacoes/{congregacao}',   [CongregacaoController::class, 'destroy'])->name('congregacoes.destroy');

    // Usuários (apenas admin)
    Route::get('/users',             [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create',      [UserController::class, 'create'])->name('users.create');
    Route::post('/users',            [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}',      [UserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}',      [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}',   [UserController::class, 'destroy'])->name('users.destroy');
});

require __DIR__.'/auth.php';