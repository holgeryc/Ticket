<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\ControllerOficinasController;
use App\Http\Controllers\RegistrosController;
use App\Http\Controllers\UsuariosController;


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
Route::get('/generar-pdf/{mesSeleccionado?}/{anioSeleccionado?}/{oficinaSeleccionado?}/{keyWord?}', [App\Http\Livewire\Registros::class, 'generarPDF'])->name('generar-pdf');

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
