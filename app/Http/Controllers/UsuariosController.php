<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UsuariosController extends Controller
{
    public function index()
    {
    if (Auth::user()->Tipo=== 'Administrador') {
        return view('livewire.users.index');
    }
    else{
        return redirect()->route('home');
    }
    }
}
