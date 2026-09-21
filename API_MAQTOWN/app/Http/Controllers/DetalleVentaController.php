<?php

namespace App\Http\Controllers;

use App\Services\DetalleVentaService;
use Illuminate\Http\Request;

class DetalleVentaController extends Controller
{
    protected $service;

    public function __construct(DetalleVentaService $service)
    {
        $this->service = $service;
    }

    public function AgregaDetalleVenta(Request $request){
        try {
            $Objeto = $request->user();

            $datos = $request->validate([
                'idventas'                 => 'required|integer',
                'productos_idproductos'    => 'required|integer',
                'cantidad'                 => 'required|numeric|min:0.01',
                'precio_venta_producto'    => 'required|numeric|min:0',
                'descuento'                => 'nullable|numeric|min:0|max:100',
            ]);

            $resultado = $this->service->ServAgregaDetalleVenta($Objeto->idusuario, $datos);

            return response()->json(['estado' => 'success', 'mensaje' => 'Producto agregado a la venta correctamente', 'data' => $resultado], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['estado' => 'error', 'mensaje' => 'Datos inválidos', 'errores' => $e->errors()], 422);
        } catch (\Illuminate\Database\QueryException $e) {
            $mensaje = $e->errorInfo[2] ?? 'No se pudo agregar el producto';
            return response()->json(['estado' => 'error', 'mensaje' => $mensaje], 409);
        } catch (\Throwable $e) {
            report($e);
            return response()->json(['estado' => 'error', 'mensaje' => 'No se pudo agregar el producto a la venta'], 500);
        }
    }

    public function EditaDetalleVenta(Request $request){
        try {
            $Objeto = $request->user();

            $datos = $request->validate([
                'iddetalle_venta'          => 'required|integer',
                'cantidad'                 => 'nullable|numeric|min:0.01',
                'precio_venta_producto'    => 'nullable|numeric|min:0',
                'descuento'                => 'nullable|numeric|min:0|max:100',
            ]);

            $resultado = $this->service->ServEditaDetalleVenta($Objeto->idusuario, $datos['iddetalle_venta'], $datos);

            return response()->json(['estado' => 'success', 'mensaje' => 'Se actualizó el detalle correctamente', 'data' => $resultado], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['estado' => 'error', 'mensaje' => 'Datos inválidos', 'errores' => $e->errors()], 422);
        } catch (\Illuminate\Database\QueryException $e) {
            $mensaje = $e->errorInfo[2] ?? 'No se pudo actualizar el detalle';
            return response()->json(['estado' => 'error', 'mensaje' => $mensaje], 409);
        } catch (\Throwable $e) {
            report($e);
            return response()->json(['estado' => 'error', 'mensaje' => 'No se pudo actualizar el detalle'], 500);
        }
    }

    public function EliminaDetalleVenta(Request $request){
        try {
            $Objeto = $request->user();

            $datos = $request->validate([
                'iddetalle_venta' => 'required|integer'
            ]);

            $resultado = $this->service->ServEliminaDetalleVenta($Objeto->idusuario, $datos['iddetalle_venta']);

            return response()->json(['estado' => 'success', 'mensaje' => 'Producto eliminado de la venta correctamente', 'data' => $resultado], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['estado' => 'error', 'mensaje' => 'Datos inválidos', 'errores' => $e->errors()], 422);
        } catch (\Illuminate\Database\QueryException $e) {
            $mensaje = $e->errorInfo[2] ?? 'No se pudo eliminar el producto';
            return response()->json(['estado' => 'error', 'mensaje' => $mensaje], 409);
        } catch (\Throwable $e) {
            report($e);
            return response()->json(['estado' => 'error', 'mensaje' => 'No se pudo eliminar el producto de la venta'], 500);
        }
    }
}