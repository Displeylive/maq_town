<?php

namespace App\Http\Controllers;

use App\Services\DetalleMovimientoCajaService;
use Illuminate\Http\Request;

class DetalleMovimientoCajaController extends Controller
{
    protected $service;

    public function __construct(DetalleMovimientoCajaService $service)
    {
        $this->service = $service;
    }

    public function RegistraGasto(Request $request){
        try {
            $Objeto = $request->user();

            $datos = $request->validate([
                'idmovimiento_caja' => 'required|integer',
                'monto'             => 'required|numeric|gt:0',
                'metodo_pago'       => 'required|string|in:efectivo,qr',
                'comentario'        => 'required|string|max:255',
            ]);

            $resultado = $this->service->ServRegistraGasto($Objeto->idusuario, $datos);

            return response()->json([
                'estado'  => 'success',
                'mensaje' => 'Gasto registrado correctamente',
                'data'    => $resultado
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'estado'  => 'error',
                'mensaje' => 'Datos inválidos',
                'errores' => $e->errors()
            ], 422);

        } catch (\Illuminate\Database\QueryException $e) {
            // Captura los SIGNAL: sesión cerrada, otro usuario, efectivo insuficiente
            $mensaje = $e->errorInfo[2] ?? 'No se pudo registrar el gasto';
            return response()->json([
                'estado'  => 'error',
                'mensaje' => $mensaje
            ], 409);

        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'estado'  => 'error',
                'mensaje' => 'No se pudo registrar el gasto'
            ], 500);
        }
    }

    public function EditaGasto(Request $request){
        try {
            $Objeto = $request->user();

            $datos = $request->validate([
                'iddetalle_movimiento_caja' => 'required|integer',
                'monto'                     => 'nullable|numeric|gt:0',
                'metodo_pago'               => 'nullable|string|in:efectivo,qr',
                'comentario'                => 'nullable|string|max:255',
            ]);

            $resultado = $this->service->ServEditaGasto($Objeto->idusuario, $datos['iddetalle_movimiento_caja'], $datos);

            return response()->json([
                'estado'  => 'success',
                'mensaje' => 'Se actualizó el gasto correctamente',
                'data'    => $resultado
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'estado'  => 'error',
                'mensaje' => 'Datos inválidos',
                'errores' => $e->errors()
            ], 422);

        } catch (\Illuminate\Database\QueryException $e) {
            $mensaje = $e->errorInfo[2] ?? 'No se pudo actualizar el gasto';
            return response()->json([
                'estado'  => 'error',
                'mensaje' => $mensaje
            ], 409);

        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'estado'  => 'error',
                'mensaje' => 'No se pudo actualizar el gasto'
            ], 500);
        }
    }

    public function EliminaGasto(Request $request){
        try {
            $Objeto = $request->user();

            $datos = $request->validate([
                'iddetalle_movimiento_caja' => 'required|integer'
            ]);

            $resultado = $this->service->ServEliminaGasto($Objeto->idusuario, $datos['iddetalle_movimiento_caja']);

            return response()->json([
                'estado'  => 'success',
                'mensaje' => 'Gasto eliminado correctamente',
                'data'    => $resultado
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'estado'  => 'error',
                'mensaje' => 'Datos inválidos',
                'errores' => $e->errors()
            ], 422);

        } catch (\Illuminate\Database\QueryException $e) {
            $mensaje = $e->errorInfo[2] ?? 'No se pudo eliminar el gasto';
            return response()->json([
                'estado'  => 'error',
                'mensaje' => $mensaje
            ], 409);

        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'estado'  => 'error',
                'mensaje' => 'No se pudo eliminar el gasto'
            ], 500);
        }
    }

    public function ListaDetalleMovimientoCaja(Request $request){
        try {
            $datos = $request->validate([
                'idmovimiento_caja' => 'required|integer',
                'tipo_movimiento'   => 'nullable|string|in:venta,gasto,anulacion venta',
            ]);

            $resultado = $this->service->ServListaDetalleMovimientoCaja(
                $datos['idmovimiento_caja'],
                $datos['tipo_movimiento'] ?? null
            );

            return response()->json([
                'estado'  => 'success',
                'mensaje' => 'Movimientos de la sesión de caja',
                'data'    => $resultado
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'estado'  => 'error',
                'mensaje' => 'Datos inválidos',
                'errores' => $e->errors()
            ], 422);

        } catch (\Illuminate\Database\QueryException $e) {
            $mensaje = $e->errorInfo[2] ?? 'No se pudo obtener el listado';
            return response()->json([
                'estado'  => 'error',
                'mensaje' => $mensaje
            ], 409);

        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'estado'  => 'error',
                'mensaje' => 'No se pudo obtener el listado de movimientos'
            ], 500);
        }
    }
}