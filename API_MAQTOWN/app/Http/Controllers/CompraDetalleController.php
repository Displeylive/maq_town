<?php

namespace App\Http\Controllers;

use App\Services\CompraDetalleService;
use Illuminate\Http\Request;

class CompraDetalleController extends Controller
{
    protected $service;

    public function __construct(CompraDetalleService $service)
    {
        $this->service = $service;
    }

    public function AgregaCompraDetalle(Request $request){
        try {
            $Objeto = $request->user();

            $datos = $request->validate([
                'idcompras'              => 'required|integer',
                'productos_idproductos'  => 'required|integer',
                'cantidad'               => 'required|numeric|min:0.01',
                'costo_unitario'         => 'required|numeric|min:0.01',
            ]);

            $resultado = $this->service->ServAgregaCompraDetalle($Objeto->idusuario, $datos);

            return response()->json([
                'estado'  => 'success',
                'mensaje' => 'Producto agregado a la compra correctamente',
                'data'    => $resultado
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'estado'  => 'error',
                'mensaje' => 'Datos inválidos',
                'errores' => $e->errors()
            ], 422);

        } catch (\Illuminate\Database\QueryException $e) {
            $mensaje = $e->errorInfo[2] ?? 'No se pudo agregar el producto';
            return response()->json([
                'estado'  => 'error',
                'mensaje' => $mensaje
            ], 409);

        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'estado'  => 'error',
                'mensaje' => 'No se pudo agregar el producto a la compra'
            ], 500);
        }
    }

    public function EditaCompraDetalle(Request $request){
        try {
            $Objeto = $request->user();

            $datos = $request->validate([
                'idcompras_detalle' => 'required|integer',
                'cantidad'          => 'nullable|numeric|min:0.01',
                'costo_unitario'    => 'nullable|numeric|min:0.01',
            ]);

            $resultado = $this->service->ServEditaCompraDetalle($Objeto->idusuario, $datos['idcompras_detalle'], $datos);

            return response()->json([
                'estado'  => 'success',
                'mensaje' => 'Se actualizó el detalle correctamente',
                'data'    => $resultado
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'estado'  => 'error',
                'mensaje' => 'Datos inválidos',
                'errores' => $e->errors()
            ], 422);

        } catch (\Illuminate\Database\QueryException $e) {
            $mensaje = $e->errorInfo[2] ?? 'No se pudo actualizar el detalle';
            return response()->json([
                'estado'  => 'error',
                'mensaje' => $mensaje
            ], 409);

        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'estado'  => 'error',
                'mensaje' => 'No se pudo actualizar el detalle'
            ], 500);
        }
    }

    public function EliminaCompraDetalle(Request $request){
        try {
            $Objeto = $request->user();

            $datos = $request->validate([
                'idcompras_detalle' => 'required|integer'
            ]);

            $resultado = $this->service->ServEliminaCompraDetalle($Objeto->idusuario, $datos['idcompras_detalle']);

            return response()->json([
                'estado'  => 'success',
                'mensaje' => 'Producto eliminado de la compra correctamente',
                'data'    => $resultado
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'estado'  => 'error',
                'mensaje' => 'Datos inválidos',
                'errores' => $e->errors()
            ], 422);

        } catch (\Illuminate\Database\QueryException $e) {
            $mensaje = $e->errorInfo[2] ?? 'No se pudo eliminar el producto';
            return response()->json([
                'estado'  => 'error',
                'mensaje' => $mensaje
            ], 409);

        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'estado'  => 'error',
                'mensaje' => 'No se pudo eliminar el producto de la compra'
            ], 500);
        }
    }
}