<?php

namespace App\Http\Controllers;

use App\Services\MovimientoCajaService;
use Illuminate\Http\Request;

class MovimientoCajaController extends Controller
{
    protected $service;

    public function __construct(MovimientoCajaService $service)
    {
        $this->service = $service;
    }

    public function AbreMovimientoCaja(Request $request){
        try {
            $Objeto = $request->user();

            $datos = $request->validate([
                'cajas_idcajas'   => 'required|integer',
                'monto_apertura'  => 'required|numeric|min:0',
            ]);

            $resultado = $this->service->ServAbreMovimientoCaja($Objeto->idusuario, $datos);

            return response()->json([
                'estado'  => 'success',
                'mensaje' => 'Caja abierta correctamente',
                'data'    => $resultado
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'estado'  => 'error',
                'mensaje' => 'Datos inválidos',
                'errores' => $e->errors()
            ], 422);

        } catch (\Illuminate\Database\QueryException $e) {
            // Captura los SIGNAL: caja inactiva, ya abierta, usuario con otra caja abierta
            $mensaje = $e->errorInfo[2] ?? 'No se pudo abrir la caja';
            return response()->json([
                'estado'  => 'error',
                'mensaje' => $mensaje
            ], 409);

        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'estado'  => 'error',
                'mensaje' => 'No se pudo abrir la caja'
            ], 500);
        }
    }

    public function CierraMovimientoCaja(Request $request){
        try {
            $Objeto = $request->user();

            $datos = $request->validate([
                'idmovimiento_caja'              => 'required|integer',
                'monto_cierre_declarado'         => 'required|numeric|min:0',
                'arqueo'                         => 'nullable|array',
                'arqueo.*.valor_denominacion'    => 'required_with:arqueo|numeric|gt:0',
                'arqueo.*.cantidad'              => 'required_with:arqueo|numeric|min:0',
            ]);

            $resultado = $this->service->ServCierraMovimientoCaja($Objeto->idusuario, $datos);

            return response()->json([
                'estado'  => 'success',
                'mensaje' => 'Caja cerrada correctamente',
                'data'    => $resultado
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'estado'  => 'error',
                'mensaje' => 'Datos inválidos',
                'errores' => $e->errors()
            ], 422);

        } catch (\Illuminate\Database\QueryException $e) {
            // Captura los SIGNAL: sesión cerrada, otro usuario, arqueo que no coincide
            $mensaje = $e->errorInfo[2] ?? 'No se pudo cerrar la caja';
            return response()->json([
                'estado'  => 'error',
                'mensaje' => $mensaje
            ], 409);

        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'estado'  => 'error',
                'mensaje' => 'No se pudo cerrar la caja'
            ], 500);
        }
    }

    public function ResumenMovimientoCaja(Request $request){
        try {
            $datos = $request->validate([
                'idmovimiento_caja' => 'required|integer'
            ]);

            $resultado = $this->service->ServResumenMovimientoCaja($datos['idmovimiento_caja']);

            return response()->json([
                'estado'  => 'success',
                'mensaje' => 'Resumen de la sesión de caja',
                'data'    => $resultado
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'estado'  => 'error',
                'mensaje' => 'Datos inválidos',
                'errores' => $e->errors()
            ], 422);

        } catch (\Illuminate\Database\QueryException $e) {
            $mensaje = $e->errorInfo[2] ?? 'No se pudo obtener el resumen';
            return response()->json([
                'estado'  => 'error',
                'mensaje' => $mensaje
            ], 409);

        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'estado'  => 'error',
                'mensaje' => 'No se pudo obtener el resumen de la sesión'
            ], 500);
        }
    }

    public function ListaMovimientoCaja(Request $request){
        try {
            $datos = $request->validate([
                'sucursales_idsucursales' => 'nullable|integer',
                'cajas_idcajas'           => 'nullable|integer',
                'estado'                  => 'nullable|string|in:abierta,cerrada',
                'fecha_inicio'            => 'nullable|date|required_with:fecha_fin',
                'fecha_fin'               => 'nullable|date|required_with:fecha_inicio|after_or_equal:fecha_inicio',
            ]);

            $resultado = $this->service->ServListaMovimientoCaja(
                $datos['sucursales_idsucursales'] ?? null,
                $datos['cajas_idcajas'] ?? null,
                $datos['estado'] ?? null,
                $datos['fecha_inicio'] ?? null,
                $datos['fecha_fin'] ?? null
            );

            return response()->json([
                'estado'  => 'success',
                'mensaje' => 'Lista de sesiones de caja',
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
                'mensaje' => 'No se pudo obtener el listado de sesiones'
            ], 500);
        }
    }

    public function SesionAbiertaUsuario(Request $request){
        try {
            $Objeto = $request->user();

            $resultado = $this->service->ServSesionAbiertaUsuario($Objeto->idusuario);

            return response()->json([
                'estado'  => 'success',
                'mensaje' => $resultado ? 'El usuario tiene una caja abierta' : 'El usuario no tiene caja abierta',
                'abierta' => $resultado !== null,
                'data'    => $resultado
            ], 200);

        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'estado'  => 'error',
                'mensaje' => 'No se pudo consultar la sesión abierta'
            ], 500);
        }
    }
}