<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\UsuarioService;

class UsuarioController extends Controller
{
      public function __construct(
        protected UsuarioService $service
        ) {}
    
        public function listaUsuario(){
            $result=$this->service->ListaUsuario();
             return response()->json([
              'data'=>$result
            ],201);
        }

    public function editaUsuario(Request $request)
    {
        try {
            $actor = $request->user(); // el que está logeado

            $datos = $request->validate([
                'id'                    => 'sometimes|integer|exists:usuario,idusuario',
                'nombre'                => 'string|max:150',
                'nombre_usuario'        => 'string|max:150',
                'telefono'              => 'integer',
                'password'              => 'string|min:6|confirmed',
                'password_confirmation' => 'string'
            ]);

            $idObjetivo = $datos['id'] ?? $actor->idusuario;
            unset($datos['id']); // no lo mandamos al update

            // Si intenta editar a OTRO usuario (no a sí mismo), validar jerarquía
            if ($idObjetivo != $actor->idusuario) {
                $usuarioObjetivo = \App\Models\AuthModel::with('rol')->findOrFail($idObjetivo);

                if (!$actor->rol || !$actor->rol->esSuperiorA($usuarioObjetivo->rol)) {
                    return response()->json([
                        'estado' => 'error',
                        'mensaje' => 'No tienes rango suficiente para editar a este usuario'
                    ], 403);
                }
            }

            $resultado = $this->service->actualizaUsuario($idObjetivo, $datos);

            return response()->json([
                'estado'  => 'success',
                'mensaje' => 'Usuario actualizado correctamente',
                'data'    => $resultado
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'estado'  => 'error',
                'mensaje' => 'Datos inválidos',
                'errores' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'estado'  => 'error',
                'mensaje' => $e->getMessage()
            ], $e->getCode() ?: 500);
        }
    }
     public function creaUsuario(Request $request)
    {
    $request->validate([
        'nombre'         => 'required|string',
        'nombreUsuario'  => 'required|string',
        'telefono'       => 'required',
        'password'       => 'required|string',
        'rolId'          => 'required|integer|exists:roles,idRoles',
    ]);

    $resultado = $this->service->creaUsuarioAdmin($request);

        return response()->json([
            'mensaje' => 'Usuario creado correctamente',
            'data' => $resultado
        ], 201);
    }

    public function eliminaUsuario(Request $request)
    {
        $request->validate(['id' => 'required|integer|exists:usuario,idusuario']);
        $this->service->eliminaUsuario($request->id);

        return response()->json(['mensaje' => 'Usuario desactivado correctamente'], 200);
    }
}