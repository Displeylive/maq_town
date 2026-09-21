<?php

namespace App\Http\Controllers;

use App\Services\SucursalService;
use Illuminate\Http\Request;

class SucursalController extends Controller
{
   // protected $service;

    public function __construct(  protected SucursalService $service)
    {
        $this->service = $service;
    }

    public function RegistraSucursal(Request $request){
        try {
            $Objeto = $request->user();

            $datos = $request->validate([
                'nombre'    => 'required|string|max:100',
                'direccion' => 'required|string|max:255',
                'ciudad'    => 'required|string|max:100',
                'celular'   => 'required|string|max:20'
            ]);

            $resultado = $this->service->ServRegistraSucursal($Objeto->idusuario, $datos);

            if ($resultado['existe']) {
                return response()->json([
                    'estado'  => 'error',
                    'mensaje' => 'Ya existe una sucursal con ese nombre',
                    'data'    => $resultado['data']
                ], 409);
            }

            return response()->json([
                'estado'  => 'success',
                'mensaje' => 'Sucursal registrada correctamente',
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
                'mensaje' => 'No se pudo registrar la sucursal'
            ], 500);
        }
    }

    public function ListaSucursal(Request $request){
        try {
            $resultado = $this->service->ServListaSucursal();

            return response()->json([
                'estado'  => 'success',
                'mensaje' => 'Lista de sucursales',
                'data'    => $resultado
            ], 200);

        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'estado'  => 'error',
                'mensaje' => 'No se pudo obtener la lista de sucursales'
            ], 500);
        }
    }

    public function EditaSucursal(Request $request){
        try {
            $Objeto = $request->user();

            $datos = $request->validate([
                'idSucursales' => 'required|integer',
                'nombre'       => 'nullable|string|max:100',
                'direccion'    => 'nullable|string|max:255',
                'ciudad'       => 'nullable|string|max:100',
                'celular'      => 'nullable|string|max:20'
            ]);

            $resultado = $this->service->ServEditaSucursal($Objeto->idusuario, $datos['idSucursales'], $datos);

            return response()->json([
                'estado'  => 'success',
                'mensaje' => 'Se actualizó la sucursal correctamente',
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
                'mensaje' => 'No se pudo actualizar la sucursal'
            ], 500);
        }
    }

    public function EliminaSucursal(Request $request){
        try {
            $datos = $request->validate([
                'idSucursales' => 'required|integer'
            ]);

            $resultado = $this->service->ServEliminaSucursal($datos['idSucursales']);

            return response()->json([
                'estado'  => 'success',
                'mensaje' => 'Sucursal eliminada correctamente',
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
                'mensaje' => 'No se pudo eliminar la sucursal'
            ], 500);
        }
    }
}