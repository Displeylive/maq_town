<?php

namespace App\Http\Controllers;

use App\Services\InventarioSucursalService;
use Illuminate\Http\Request;

class InventarioSucursalController extends Controller
{
    protected $service;

    public function __construct(InventarioSucursalService $service)
    {
        $this->service = $service;
    }

    public function ListaInventarioSucursal(Request $request){
        try {
            $datos = $request->validate([
                'nombre_sucursal' => 'nullable|string|max:100'
            ]);

            $nombreSucursal = $datos['nombre_sucursal'] ?? null;

            $resultado = $this->service->ServListaInventarioSucursal($nombreSucursal);

            return response()->json([
                'estado'  => 'success',
                'mensaje' => 'Lista de inventario por sucursal',
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
                'mensaje' => 'No se pudo obtener el inventario'
            ], 500);
        }
    }

    public function EditaInventarioSucursal(Request $request){
        try {
            $Objeto = $request->user();

            $datos = $request->validate([
                'idinventario_sucursal' => 'required|integer',
                'Stock_minino'          => 'nullable|numeric',
                'ubicacion_pasillo'     => 'nullable|string|max:100'
            ]);

            $resultado = $this->service->ServEditaInventarioSucursal($Objeto->idusuario, $datos['idinventario_sucursal'], $datos);

            return response()->json([
                'estado'  => 'success',
                'mensaje' => 'Se actualizó el inventario correctamente',
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
                'mensaje' => 'No se pudo actualizar el inventario'
            ], 500);
        }
    }
}