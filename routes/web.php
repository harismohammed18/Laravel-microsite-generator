<?php

use App\Http\Controllers\TournamentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [TournamentController::class, 'index'])->name('dashboard');
    Route::get('/tournament', [TournamentController::class, 'create'])->name('tournament.create');
    Route::post('/tournament', [TournamentController::class, 'store'])->name('tournament.store');
    Route::get('/tournament/{id}', [TournamentController::class, 'edit'])->name('tournament.edit');
    Route::get('/tournament/delete/{id}', [TournamentController::class, 'destroy'])->name('tournament.delete');
    Route::post('/tournament/{id}', [TournamentController::class, 'update'])->name('tournament.update');
    Route::get('/tournament/download/{id}', [TournamentController::class, 'download'])->name('tournament.download');
});

require __DIR__ . '/auth.php';
