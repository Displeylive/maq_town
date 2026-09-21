<?php

namespace App\Http\Controllers;

use App\Services\VentaService;
use Illuminate\Http\Request;

class VentaController extends Controller
{
    protected $service;

    public function __construct(VentaService $service)
    {
        $this->service = $service;
    }

    public function RegistraVenta(Request $request){
        try {
            $Objeto = $request->user();

            $datos = $request->validate([
                'tipo_venta'                     => 'required|string|in:mayor,menor',
                'tipo_pago'                      => 'required|string|in:contado,credito',
                'metodo_pago'                    => 'required|string|max:45',
                'metodo_entrega'                 => 'required|string|max:150',
                'estado_venta'                   => 'required|string|in:pendiente,completada,anulada',
                'sucursales_idsucursales'        => 'required|integer',
                'clientes_idclientes'            => 'required|integer',
                'productos'                              => 'required|array|min:1',
                'productos.*.productos_idproductos'      => 'required|integer',
                'productos.*.cantidad'                   => 'required|numeric|min:0.01',
                'productos.*.precio_venta_producto'      => 'required|numeric|min:0',
                'productos.*.descuento'                  => 'nullable|numeric|min:0|max:100',
            ]);

            $resultado = $this->service->ServRegistraVenta($Objeto->idusuario, $datos);

            return response()->json([
                'estado'  => 'success',
                'mensaje' => 'Venta registrada correctamente',
                'data'    => $resultado
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'estado'  => 'error',
                'mensaje' => 'Datos inválidos',
                'errores' => $e->errors()
            ], 422);

        } catch (\Illuminate\Database\QueryException $e) {
            // Captura los SIGNAL: stock insuficiente o límite de crédito superado
            $mensaje = $e->errorInfo[2] ?? 'No se pudo registrar la venta';
            return response()->json([
                'estado'  => 'error',
                'mensaje' => $mensaje
            ], 409);

        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'estado'  => 'error',
                'mensaje' => 'No se pudo registrar la venta'
            ], 500);
        }
    }

    public function ListaVenta(Request $request){
        try {
            $resultado = $this->service->ServListaVenta();
            return response()->json(['estado' => 'success', 'mensaje' => 'Lista de ventas', 'data' => $resultado], 200);
        } catch (\Throwable $e) {
            report($e);
            return response()->json(['estado' => 'error', 'mensaje' => 'No se pudo obtener la lista de ventas'], 500);
        }
    }

    public function ListaVentaAnulada(Request $request){
        try {
            $resultado = $this->service->ServListaVentaAnulada();
            return response()->json(['estado' => 'success', 'mensaje' => 'Lista de ventas anuladas', 'data' => $resultado], 200);
        } catch (\Throwable $e) {
            report($e);
            return response()->json(['estado' => 'error', 'mensaje' => 'No se pudo obtener la lista de ventas anuladas'], 500);
        }
    }

    public function buscarIdVenta(Request $request){
        try {
            $datos = $request->validate(['idventas' => 'required|integer']);
            $resultado = $this->service->ServBuscarIdVenta($datos['idventas']);
            return response()->json(['estado' => 'success', 'mensaje' => 'Venta encontrada', 'data' => $resultado], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['estado' => 'error', 'mensaje' => 'Datos inválidos', 'errores' => $e->errors()], 422);
        } catch (\Throwable $e) {
            report($e);
            return response()->json(['estado' => 'error', 'mensaje' => 'No se pudo obtener la venta'], 500);
        }
    }

    public function EditaVenta(Request $request){
        try {
            $Objeto = $request->user();

            $datos = $request->validate([
                'idventas'          => 'required|integer',
                'tipo_venta'        => 'nullable|string|in:mayor,menor',
                'metodo_pago'       => 'nullable|string|max:45',
                'metodo_entrega'    => 'nullable|string|max:150',
                'estado_venta'      => 'nullable|string|in:pendiente,completada',
            ]);

            $resultado = $this->service->ServEditaVenta($Objeto->idusuario, $datos['idventas'], $datos);

            return response()->json(['estado' => 'success', 'mensaje' => 'Se actualizó la venta correctamente', 'data' => $resultado], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['estado' => 'error', 'mensaje' => 'Datos inválidos', 'errores' => $e->errors()], 422);
        } catch (\Illuminate\Database\QueryException $e) {
            $mensaje = $e->errorInfo[2] ?? 'No se pudo actualizar la venta';
            return response()->json(['estado' => 'error', 'mensaje' => $mensaje], 409);
        } catch (\Throwable $e) {
            report($e);
            return response()->json(['estado' => 'error', 'mensaje' => 'No se pudo actualizar la venta'], 500);
        }
    }

    public function AnulaVenta(Request $request){
        try {
            $Objeto = $request->user();
            $datos = $request->validate(['idventas' => 'required|integer']);

            $resultado = $this->service->ServAnulaVenta($Objeto->idusuario, $datos['idventas']);

            return response()->json(['estado' => 'success', 'mensaje' => 'Venta anulada correctamente', 'data' => $resultado], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['estado' => 'error', 'mensaje' => 'Datos inválidos', 'errores' => $e->errors()], 422);
        } catch (\Illuminate\Database\QueryException $e) {
            $mensaje = $e->errorInfo[2] ?? 'No se pudo anular la venta';
            return response()->json(['estado' => 'error', 'mensaje' => $mensaje], 409);
        } catch (\Throwable $e) {
            report($e);
            return response()->json(['estado' => 'error', 'mensaje' => 'No se pudo anular la venta'], 500);
        }
    }
}