<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckUserActivity
{
    public function handle($request, Closure $next)
    {
        // Verificar si el usuario está autenticado
        if (Auth::check()) {
            $lastActivity = session('last_activity');

            // Verificar si ha pasado más de 1 minuto desde la última actividad
            if ($lastActivity && time() - $lastActivity > 300) {
                Auth::logout();
                session()->flush();
                return redirect()->route('login')->with('timeout', 'Tu sesión ha expirado debido a 5 minutos de inactividad.');
            }

            // Actualizar el tiempo de última actividad
            session(['last_activity' => time()]);
        }

        return $next($request);
    }
}
