<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerificaPermiso
{
    public function handle(Request $request, Closure $next, string $permiso): Response
    {
        $usuario = $request->user();

        if (!$usuario) {
            return response()->json(['message' => 'No autenticado'], 401);
        }

        $tienePermiso = $usuario->rol
            ?->permisos()
            ->where('nombre', $permiso)
            ->where('rol_permiso.activo', true)
            ->where('permisos.activo', true)
            ->exists();

        if (!$tienePermiso) {
            return response()->json(['message' => 'No tienes permiso para esta acción'], 403);
        }

        return $next($request);
    }
}