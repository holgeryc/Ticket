<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class OficinasController extends Controller
{
    public function index()
    {
        if (Auth::user()->Tipo=== 'Administrador'|| Auth::user()->Tipo=== 'Centro_computo') {
            # code...
            return view('livewire.oficinas.index');
        }
        else{
            return redirect()->route('home');
        }
    }

}
