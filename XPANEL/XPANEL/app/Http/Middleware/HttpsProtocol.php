<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HttpsProtocol
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */

     public function handle(Request $request, Closure $next)
     {
         // Define las rutas que se deben excluir de la redirección HTTPS
         $excludedRoutes = [
             'updates/*',
             // Agrega más rutas aquí según tus necesidades
         ];
 
         // Verifica si la ruta actual se debe excluir de la redirección HTTPS
         foreach ($excludedRoutes as $route) {
             if ($request->is($route)) {
                 return $next($request);
             }
         }
 
         // Redirige a la versión segura solo si la solicitud no es segura y estamos en producción
         if (!$request->secure() && app()->environment() === 'production') {
             return redirect()->secure($request->getRequestUri());
         }
 
         return $next($request);
     }
}
