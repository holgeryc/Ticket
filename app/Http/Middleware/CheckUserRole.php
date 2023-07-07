<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Route;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Obtén el usuario autenticado
        $user = $request->user();

        // Verifica si el usuario tiene uno de los roles permitidos
        if ($user && in_array($user->Tipo, ['Administrador', 'Centro_computo', 'Personal_Geredu'])) {
            return $next($request);
        }

        // Si no tiene un rol válido, redirige a alguna página de error o realiza una acción adecuada
        return redirect()->route('auth.login');
    }
}
