<?php

namespace App\Http\Controllers;

use App\Services\PrecioCompraProductoService;
use Illuminate\Http\Request;

class PrecioCompraProductoController extends Controller
{
    protected $service;

    public function __construct(PrecioCompraProductoService $service)
    {
        $this->service = $service;
    }

    public function ListaPrecioCompraProducto(Request $request){
        try {
            $datos = $request->validate([
                'productos_idproductos' => 'nullable|integer',
                'fecha_inicio'          => 'nullable|date|required_with:fecha_fin',
                'fecha_fin'             => 'nullable|date|required_with:fecha_inicio|after_or_equal:fecha_inicio',
            ]);

            $resultado = $this->service->ServListaPrecioCompraProducto(
                $datos['productos_idproductos'] ?? null,
                $datos['fecha_inicio'] ?? null,
                $datos['fecha_fin'] ?? null
            );

            return response()->json([
                'estado'  => 'success',
                'mensaje' => 'Lista de precios de compra',
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
                'mensaje' => 'No se pudo obtener el listado de precios de compra'
            ], 500);
        }
    }
}