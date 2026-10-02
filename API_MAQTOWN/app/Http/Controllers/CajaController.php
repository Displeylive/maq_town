<?php

namespace App\Http\Controllers;

use App\Services\CajaService;
use Illuminate\Http\Request;

class CajaController extends Controller
{
    protected $service;

    public function __construct(CajaService $service)
    {
        $this->service = $service;
    }

    public function RegistraCaja(Request $request){
        try {
            $Objeto = $request->user();

            $datos = $request->validate([
                'nombre_caja'              => 'required|string|max:45',
                'descripcion'              => 'nullable|string|max:255',
                'sucursales_idsucursales'  => 'required|integer',
            ]);

            $resultado = $this->service->ServRegistraCaja($Objeto->idusuario, $datos);

            if ($resultado['existe']) {
                return response()->json([
                    'estado'  => 'error',
                    'mensaje' => 'Ya existe una caja con ese nombre en la sucursal',
                    'data'    => $resultado['data']
                ], 409);
            }

            return response()->json([
                'estado'  => 'success',
                'mensaje' => 'Caja registrada correctamente',
                'data'    => $resultado['data']
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'estado'  => 'error',
                'mensaje' => 'Datos inválidos',
                'errores' => $e->errors()
            ], 422);

        } catch (\Illuminate\Database\QueryException $e) {
            // 1062 = duplicado detectado por el índice único (respaldo)
            if (($e->errorInfo[1] ?? null) == 1062) {
                return response()->json([
                    'estado'  => 'error',
                    'mensaje' => 'Ya existe una caja con ese nombre en la sucursal'
                ], 409);
            }
            $mensaje = $e->errorInfo[2] ?? 'No se pudo registrar la caja';
            return response()->json([
                'estado'  => 'error',
                'mensaje' => $mensaje
            ], 409);

        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'estado'  => 'error',
                'mensaje' => 'No se pudo registrar la caja'
            ], 500);
        }
    }

    public function ListaCaja(Request $request){
        try {
            $datos = $request->validate([
                'sucursales_idsucursales' => 'nullable|integer',
                'estado'                  => 'nullable|string|in:activa,desactiva',
            ]);

            $resultado = $this->service->ServListaCaja(
                $datos['sucursales_idsucursales'] ?? null,
                $datos['estado'] ?? null
            );

            return response()->json([
                'estado'  => 'success',
                'mensaje' => 'Lista de cajas',
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
                'mensaje' => 'No se pudo obtener la lista de cajas'
            ], 500);
        }
    }

    public function EditaCaja(Request $request){
        try {
            $Objeto = $request->user();

            $datos = $request->validate([
                'idcajas'      => 'required|integer',
                'nombre_caja'  => 'nullable|string|max:45',
                'descripcion'  => 'nullable|string|max:255',
            ]);

            $resultado = $this->service->ServEditaCaja($Objeto->idusuario, $datos['idcajas'], $datos);

            return response()->json([
                'estado'  => 'success',
                'mensaje' => 'Se actualizó la caja correctamente',
                'data'    => $resultado
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'estado'  => 'error',
                'mensaje' => 'Datos inválidos',
                'errores' => $e->errors()
            ], 422);

        } catch (\Illuminate\Database\QueryException $e) {
            $mensaje = $e->errorInfo[2] ?? 'No se pudo actualizar la caja';
            return response()->json([
                'estado'  => 'error',
                'mensaje' => $mensaje
            ], 409);

        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'estado'  => 'error',
                'mensaje' => 'No se pudo actualizar la caja'
            ], 500);
        }
    }

    public function DesactivaCaja(Request $request){
        try {
            $Objeto = $request->user();

            $datos = $request->validate([
                'idcajas' => 'required|integer'
            ]);

            $resultado = $this->service->ServDesactivaCaja($Objeto->idusuario, $datos['idcajas']);

            return response()->json([
                'estado'  => 'success',
                'mensaje' => 'Caja desactivada correctamente',
                'data'    => $resultado
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'estado'  => 'error',
                'mensaje' => 'Datos inválidos',
                'errores' => $e->errors()
            ], 422);

        } catch (\Illuminate\Database\QueryException $e) {
            // Captura el SIGNAL: caja no encontrada o con sesión abierta
            $mensaje = $e->errorInfo[2] ?? 'No se pudo desactivar la caja';
            return response()->json([
                'estado'  => 'error',
                'mensaje' => $mensaje
            ], 409);

        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'estado'  => 'error',
                'mensaje' => 'No se pudo desactivar la caja'
            ], 500);
        }
    }

    public function ActivaCaja(Request $request){
        try {
            $Objeto = $request->user();

            $datos = $request->validate([
                'idcajas' => 'required|integer'
            ]);

            $resultado = $this->service->ServActivaCaja($Objeto->idusuario, $datos['idcajas']);

            return response()->json([
                'estado'  => 'success',
                'mensaje' => 'Caja activada correctamente',
                'data'    => $resultado
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'estado'  => 'error',
                'mensaje' => 'Datos inválidos',
                'errores' => $e->errors()
            ], 422);

        } catch (\Illuminate\Database\QueryException $e) {
            $mensaje = $e->errorInfo[2] ?? 'No se pudo activar la caja';
            return response()->json([
                'estado'  => 'error',
                'mensaje' => $mensaje
            ], 409);

        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'estado'  => 'error',
                'mensaje' => 'No se pudo activar la caja'
            ], 500);
        }
    }
}