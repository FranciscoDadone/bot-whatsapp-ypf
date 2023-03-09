<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ConfigurationController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\TicketsController;
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
    return redirect('tickets');
});

Route::get('/tickets', function () {
    return view('tickets');
})->middleware(['auth', 'verified'])->name('tickets');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/configuracion/numeros', [ConfigurationController::class, 'numeros'])->name('configuracion.numeros')->middleware('permissions:1');
    Route::post('/configuracion/numeros/delete/{id}', [ConfigurationController::class, 'delete_number'])->name('configuracion.numeros.delete')->middleware('permissions:1');
    Route::post('/configuracion/numeros/new', [ConfigurationController::class, 'new_number'])->name('configuracion.numeros.new')->middleware('permissions:1');

    Route::post('/tickets/delete/{id}', [TicketsController::class, 'delete'])->name('tickets.delete')->middleware('permissions:1');
    Route::get('/ticket/ver/{id}', [TicketsController::class, 'view_ticket'])->name('ticket.view_ticket');
    Route::post('/ticket/{id}/status/{status}', [TicketsController::class, 'change_status'])->name('ticket.change_status');
    Route::post('/ticket/assign_ticket', [TicketsController::class, 'assign_ticket'])->name('ticket.assign');

    Route::get('/usuarios', [UsersController::class, 'usuarios'])->name('usuarios')->middleware('permissions:1');
    Route::post('/usuarios/new', [UsersController::class, 'new'])->name('usuarios.new')->middleware('permissions:1');
});

require __DIR__.'/auth.php';
