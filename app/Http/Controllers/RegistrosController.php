<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Dompdf\Dompdf;
use App\Models\Registro;

class RegistrosController extends Controller
{
    public function index()
    {
        if (Auth::user()->Tipo=== 'Administrador' || Auth::user()->Tipo=== 'Centro_computo' || Auth::user()->Tipo=== 'Personal_Geredu') {
            return view('livewire.registros.index');
        }
        else{
            return redirect()->route('home');
        }
    }
}
