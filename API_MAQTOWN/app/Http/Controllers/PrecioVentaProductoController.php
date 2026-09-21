<?php

namespace App\Http\Controllers;

use App\Services\PrecioVentaProductoService;
use Illuminate\Http\Request;

class PrecioVentaProductoController extends Controller
{
    protected $service;

    public function __construct(PrecioVentaProductoService $service)
    {
        $this->service = $service;
    }

    public function RegistraPrecioVentaProducto(Request $request){
        try {
            $Objeto = $request->user();

            $datos = $request->validate([
                'tipo_cliente'              => 'required|string|max:45',
                'precio_venta'              => 'required|numeric',
                'cantidad_minima'           => 'required|numeric',
                'limite_descuento'          => 'nullable|string|max:45',
                'productos_idproductos'     => 'required|integer',
            ]);

            $resultado = $this->service->ServRegistraPrecioVentaProducto($Objeto->idusuario, $datos);

            return response()->json([
                'estado'  => 'success',
                'mensaje' => 'Precio de venta registrado correctamente',
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
                'mensaje' => 'No se pudo registrar el precio de venta'
            ], 500);
        }
    }

    public function ListaPrecioVentaProducto(Request $request){
        try {
            $resultado = $this->service->ServListaPrecioVentaProducto();

            return response()->json([
                'estado'  => 'success',
                'mensaje' => 'Lista de precios de venta',
                'data'    => $resultado
            ], 200);

        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'estado'  => 'error',
                'mensaje' => 'No se pudo obtener la lista de precios'
            ], 500);
        }
    }

    public function EditaPrecioVentaProducto(Request $request){
        try {
            $Objeto = $request->user();

            $datos = $request->validate([
                'idprecio_venta_producto'  => 'required|integer',
                'tipo_cliente'             => 'nullable|string|max:45',
                'precio_venta'             => 'nullable|numeric',
                'cantidad_minima'          => 'nullable|numeric',
                'limite_descuento'         => 'nullable|string|max:45',
            ]);

            $resultado = $this->service->ServEditaPrecioVentaProducto($Objeto->idusuario, $datos['idprecio_venta_producto'], $datos);

            return response()->json([
                'estado'  => 'success',
                'mensaje' => 'Se actualizó el precio de venta correctamente',
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
                'mensaje' => 'No se pudo actualizar el precio de venta'
            ], 500);
        }
    }

    public function EliminaPrecioVentaProducto(Request $request){
        try {
            $datos = $request->validate([
                'idprecio_venta_producto' => 'required|integer'
            ]);

            $resultado = $this->service->ServEliminaPrecioVentaProducto($datos['idprecio_venta_producto']);

            return response()->json([
                'estado'  => 'success',
                'mensaje' => 'Precio de venta eliminado correctamente',
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
                'mensaje' => 'No se pudo eliminar el precio de venta'
            ], 500);
        }
    }
}