<?php

namespace App\Http\Controllers;

use App\Services\DetalleArqueoService;
use Illuminate\Http\Request;

class DetalleArqueoController extends Controller
{
    protected $service;

    public function __construct(DetalleArqueoService $service)
    {
        $this->service = $service;
    }

    public function ListaDetalleArqueo(Request $request){
        try {
            $datos = $request->validate([
                'idmovimiento_caja' => 'required|integer'
            ]);

            $resultado = $this->service->ServListaDetalleArqueo($datos['idmovimiento_caja']);

            return response()->json([
                'estado'  => 'success',
                'mensaje' => 'Detalle del arqueo',
                'data'    => $resultado
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'estado'  => 'error',
                'mensaje' => 'Datos inválidos',
                'errores' => $e->errors()
            ], 422);

        } catch (\Illuminate\Database\QueryException $e) {
            $mensaje = $e->errorInfo[2] ?? 'No se pudo obtener el arqueo';
            return response()->json([
                'estado'  => 'error',
                'mensaje' => $mensaje
            ], 409);

        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'estado'  => 'error',
                'mensaje' => 'No se pudo obtener el detalle del arqueo'
            ], 500);
        }
    }
}