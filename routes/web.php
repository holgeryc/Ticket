<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\OficinasController;
use App\Http\Controllers\RegistrosController;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\UgelesController;
use App\Http\Controllers\TicketsController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/', function () {
    if (Auth::check()) {
        return view('home');
    } else {
        return view('auth.login');
    }
});


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/generar-pdf/{oficinaSeleccionado?}/{keyWord?}', [App\Http\Livewire\Registros::class, 'generarPDF'])->name('generar-pdf');
Route::get('/generar_pdf/{ticketSeleccionado?}/{keyWord?}', [App\Http\Livewire\Tickets::class, 'generar_PDF'])->name('generar_pdf');

//Route Hooks - Do not delete//
Route::middleware(['auth', 'checkUserRole:Administrador'])
    ->get('/oficinas', [OficinasController::class, 'index'])
    ->name('oficinas.index');   

Route::middleware(['auth', 'checkUserRole:Administrador'])
    ->get('/registros', [RegistrosController::class, 'index'])
    ->name('registros.index');

Route::middleware(['auth', 'checkUserRole:Administrador'])
    ->get('/users', [UsuariosController::class, 'index'])
    ->name('users.index');

Route::middleware(['auth', 'checkUserRole:Administrador'])
    ->get('/ugeles', [UgelesController::class, 'index'])
    ->name('ugeles.index');

Route::middleware(['auth', 'checkUserRole:Administrador'])
    ->get('/tickets', [TicketsController::class, 'index'])
    ->name('tickets.index');
