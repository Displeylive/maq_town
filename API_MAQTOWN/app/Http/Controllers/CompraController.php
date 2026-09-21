<?php

namespace App\Http\Controllers;

use App\Services\CompraService;
use Illuminate\Http\Request;

class CompraController extends Controller
{
    protected $service;

    public function __construct(CompraService $service)
    {
        $this->service = $service;
    }

    public function RegistraCompra(Request $request){
        try {
            $Objeto = $request->user();

            $datos = $request->validate([
                'numero_factura_prov'          => 'required|string|max:45',
                'estado_pago'                  => 'required|string|max:45',
                'fecha_limite_pago'            => 'required|date',
                'proveedores_idproveedores'    => 'required|integer',
                'sucursales_idsucursales'      => 'required|integer',
                'detalle'                            => 'required|array|min:1',
                'productos.*.productos_idproductos'    => 'required|integer',
                'productos.*.cantidad'                 => 'required|numeric|min:0.01',
                'productos.*.costo_unitario'           => 'required|numeric|min:0.01',
            ]);

            $resultado = $this->service->ServRegistraCompra($Objeto->idusuario, $datos);

            return response()->json([
                'estado'  => 'success',
                'mensaje' => 'Compra registrada correctamente',
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
                'mensaje' => 'No se pudo registrar la compra'
            ], 500);
        }
    }

    public function ListaCompra(Request $request){
        try {
            $resultado = $this->service->ServListaCompra();

            return response()->json([
                'estado'  => 'success',
                'mensaje' => 'Lista de compras',
                'data'    => $resultado
            ], 200);

        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'estado'  => 'error',
                'mensaje' => 'No se pudo obtener la lista de compras'
            ], 500);
        }
    }


    public function EditaCompra(Request $request){
        try {
            $Objeto = $request->user();

            $datos = $request->validate([
                'idcompras'            => 'required|integer',
                'numero_factura_prov'  => 'nullable|string|max:45',
                'total_compra'         => 'nullable|numeric',
                'fecha_compra'         => 'nullable|date',
                'estado_pago'          => 'nullable|string|max:45',
                'fecha_limite_pago'    => 'nullable|date',
            ]);

            $resultado = $this->service->ServEditaCompra($Objeto->idusuario, $datos['idcompras'], $datos);

            return response()->json([
                'estado'  => 'success',
                'mensaje' => 'Se actualizó la compra correctamente',
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
                'mensaje' => 'No se pudo actualizar la compra'
            ], 500);
        }
    }

    public function buscarIdCompra(Request $request){
        try {
            $datos = $request->validate([
                'idcompras' => 'required|integer'
            ]);

            $resultado = $this->service->ServBuscarIdCompra($datos['idcompras']);

            return response()->json([
                'estado'  => 'success',
                'mensaje' => 'Compra encontrada',
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
                'mensaje' => 'No se pudo obtener la compra'
            ], 500);
        }
    }

    public function EliminaCompra(Request $request){
        try {
            $Objeto = $request->user();

            $datos = $request->validate([
                'idcompras' => 'required|integer'
            ]);

            $resultado = $this->service->ServEliminaCompra($Objeto->idusuario, $datos['idcompras']);

            return response()->json([
                'estado'  => 'success',
                'mensaje' => 'Compra eliminada correctamente',
                'data'    => $resultado
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'estado'  => 'error',
                'mensaje' => 'Datos inválidos',
                'errores' => $e->errors()
            ], 422);

        } catch (\Illuminate\Database\QueryException $e) {
            // Captura el SIGNAL: "Compra no encontrada" o "stock insuficiente"
            $mensaje = $e->errorInfo[2] ?? 'No se pudo eliminar la compra';
            return response()->json([
                'estado'  => 'error',
                'mensaje' => $mensaje
            ], 409);

        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'estado'  => 'error',
                'mensaje' => 'No se pudo eliminar la compra'
            ], 500);
        }
    }

}