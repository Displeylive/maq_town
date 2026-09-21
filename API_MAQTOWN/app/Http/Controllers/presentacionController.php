<?php

namespace App\Http\Controllers;
use App\Services\PresentacionService;
use Illuminate\Http\Request;

class PresentacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct(
        protected  PresentacionService $service
        ) {}


    /**
     * Show the form for creating a new resource.
     */
    public function RegistraPresentacion (Request $request)
    {
        //
        try {
            $Objeto = $request->user();

            $datos = $request->validate([
                'nombre_presentacion' => 'string|max:45',
                'abreviatura'         => 'string|max:20',
                'descripcion'         => 'string|max:250'
            ]);

            $resultado = $this->service->ServRegistraPresentacion($Objeto->idusuario, $datos);

            return response()->json([
                'estado'  => 'success',
                'mensaje' => 'Presentación registrada correctamente',
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
                'mensaje' => 'No se pudo registrar la presentación'
            ], 500);
        }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function ListaPresentacion (Request $request)
    {
        try {
            $resultado = $this->service->ServListaPresentacion();

            return response()->json([
                'estado'  => 'success',
                'mensaje' => 'Lista de presentaciones',
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
                'mensaje' => 'No se pudo obtener la lista de presentaciones'
            ], 500);
        }
            
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function EditaPresentacion(Request $request)
    {
        //
        try {
            $Objeto = $request->user();

            $datos = $request->validate([
                'idPresentacion'       => 'integer|required',
                'nombre_presentacion'  => 'string|max:45',
                'abreviatura'          => 'string|max:20',
                'descripcion'          => 'string|max:250'
            ]);

            $resultado = $this->service->ServEditaPresentacion($Objeto->idusuario, $datos['idPresentacion'], $datos);

            return response()->json([
                'estado'  => 'success',
                'mensaje' => 'Se actualizó el registro correctamente',
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
                'mensaje' => 'No se pudo actualizar la presentación'
            ], 500);
        }


    }

    /**
     * Update the specified resource in storage.
     */
    public function EliminaPresentacion(Request $request)
    {
        //

        try {
            $datos = $request->validate([
                'idPresentacion' => 'integer|required'
            ]);

            $resultado = $this->service->ServEliminaPresentacion($datos['idPresentacion']);

            return response()->json([
                'estado'  => 'success',
                'mensaje' => 'Presentación eliminada correctamente',
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
                'mensaje' => 'No se pudo eliminar la presentación'
            ], 500);
        }
    }
    
    
}
