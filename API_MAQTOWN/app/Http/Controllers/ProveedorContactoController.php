<?php

namespace App\Http\Controllers;

use App\Services\ProveedorContactoService;
use Illuminate\Http\Request;

class ProveedorContactoController extends Controller
{
    protected $service;

    public function __construct(ProveedorContactoService $service)
    {
        $this->service = $service;
    }

    public function RegistraProveedorContacto(Request $request){
        try {
            $Objeto = $request->user();

            $datos = $request->validate([
                'contacto_nombre'              => 'required|string|max:100',
                'email'                        => 'required|email|max:100',
                'telefono'                     => 'required|string|max:20',
                'proveedores_idproveedores'    => 'required|integer',
            ]);

            $resultado = $this->service->ServRegistraProveedorContacto($Objeto->idusuario, $datos);

            if ($resultado['existe']) {
                return response()->json([
                    'estado'  => 'error',
                    'mensaje' => 'Ya existe un contacto con ese nombre para este proveedor',
                    'data'    => $resultado['data']
                ], 409);
            }

            return response()->json([
                'estado'  => 'success',
                'mensaje' => 'Contacto de proveedor registrado correctamente',
                'data'    => $resultado['data']
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'estado'  => 'error',
                'mensaje' => 'Datos inválidos',
                'errores' => $e->errors()
            ], 422);

        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'estado'  => 'error',
                'mensaje' => 'No se pudo registrar el contacto de proveedor'
            ], 500);
        }
    }

    public function ListaProveedorContacto(Request $request){
        try {
            $datos = $request->validate([
                'proveedores_idproveedores' => 'nullable|integer'
            ]);

            $resultado = $this->service->ServListaProveedorContacto($datos['proveedores_idproveedores'] ?? null);

            return response()->json([
                'estado'  => 'success',
                'mensaje' => 'Lista de contactos de proveedor',
                'data'    => $resultado
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'estado'  => 'error',
                'mensaje' => 'Datos inválidos',
                'errores' => $e->errors()
            ], 422);

        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'estado'  => 'error',
                'mensaje' => 'No se pudo obtener la lista de contactos'
            ], 500);
        }
    }

    public function EditaProveedorContacto(Request $request){
        try {
            $Objeto = $request->user();

            $datos = $request->validate([
                'idProveedor_Contacto'  => 'required|integer',
                'contacto_nombre'       => 'nullable|string|max:100',
                'email'                 => 'nullable|email|max:100',
                'telefono'              => 'nullable|string|max:20',
            ]);

            $resultado = $this->service->ServEditaProveedorContacto($Objeto->idusuario, $datos['idProveedor_Contacto'], $datos);

            return response()->json([
                'estado'  => 'success',
                'mensaje' => 'Se actualizó el contacto correctamente',
                'data'    => $resultado
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'estado'  => 'error',
                'mensaje' => 'Datos inválidos',
                'errores' => $e->errors()
            ], 422);

        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'estado'  => 'error',
                'mensaje' => 'No se pudo actualizar el contacto'
            ], 500);
        }
    }

    public function EliminaProveedorContacto(Request $request){
        try {
            $datos = $request->validate([
                'idProveedor_Contacto' => 'required|integer'
            ]);

            $resultado = $this->service->ServEliminaProveedorContacto($datos['idProveedor_Contacto']);

            return response()->json([
                'estado'  => 'success',
                'mensaje' => 'Contacto eliminado correctamente',
                'data'    => $resultado
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'estado'  => 'error',
                'mensaje' => 'Datos inválidos',
                'errores' => $e->errors()
            ], 422);

        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'estado'  => 'error',
                'mensaje' => 'No se pudo eliminar el contacto'
            ], 500);
        }
    }
}