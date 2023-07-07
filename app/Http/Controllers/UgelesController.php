<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class UgelesController extends Controller
{
    public function index()
    {
        if (Auth::user()->Tipo=== 'Administrador') {
            # code...
            return view('livewire.ugeles.index');
        }
        else{
            return redirect()->route('home');
        }
    }

}