<?php

namespace App\Http\Controllers;
use App\Services\categoriaService;
use Illuminate\Http\Request;

class categoriaController extends Controller
{
        public function __construct(
        protected  categoriaService $service
        ) {}

    public function RegistraCategoria(Request $request){  
        try{ 
               //usuario en session
              
                $Objeto = $request->user();
                // ✅ Un solo validate con todas las reglas
                $datos = $request->validate([
                   
                    'nombre'               => 'string|max:50',
                    'slug'                 =>'string|max:50',
                    'imagen_url'           =>'string|max:50',
                    'descripcion'          => 'string|max:50'
                ]);
                // precesa registro de cliente
                
             $resultado = $this->service->ServRegistraCategoria($Objeto->idusuario , $datos);
                return response()->json([
                    'estado'  => 'success',
                    'mensaje' => 'Categoria registrado correctamente',
                    'data'    => $datos
                ], 200);

           } catch (\Illuminate\Validation\ValidationException $e) {
                // ✅ Captura específica para errores de validación
                return response()->json([
                    'estado'  => 'error',
                    'mensaje' => 'Datos inválidos',
                    'errores' => $e->errors()
                ], 422);

            } 
            catch (\Throwable $e) {
                report($e); // queda en tus logs para diagnóstico
                return response()->json([
                    'estado'  => 'error',
                    'mensaje' => 'No se pudo registrar la categoría'
                ], 500);
            }

    }
        public function ListaCategoria(Request $request){  
            try {
             $resultado = $this->service->ServListaCategoria();
                return response()->json([
                    'estado'  => 'success',
                    'mensaje' => 'Lista de categoroias',
                    'data'    => $resultado
                ], 200);

           } catch (\Illuminate\Validation\ValidationException $e) {
                // ✅ Captura específica para errores de validación
                return response()->json([
                    'estado'  => 'error',
                    'mensaje' => 'Datos inválidos',
                    'errores' => $e->errors()
                ], 422);

            } 

        }
        
        public function EditaCategoria(Request $request){
            try {
                $Objeto = $request->user();

                $datos = $request->validate([
                    'idcategorias' => 'integer|required',
                    'nombre'       => 'string|max:100',
                    'slug'         => 'string|max:255',
                    'imagen_url'   => 'string|max:255',
                    'descripcion'  => 'string|max:255'
                ]);

                $resultado = $this->service->ServEditaCategoria($Objeto->idusuario, $datos['idcategorias'], $datos);

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
            }
        }


       public function EliminaCategoria(Request $request){
        try {
            $datos = $request->validate([
                'idcategorias' => 'integer|required'
            ]);

            $resultado = $this->service->ServEliminaCategoria($datos['idcategorias']);

            return response()->json([
                'estado'  => 'success',
                'mensaje' => 'Categoría eliminada correctamente',
                'data'    => $resultado
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'estado'  => 'error',
                'mensaje' => 'Datos inválidos',
                'errores' => $e->errors()
            ], 422);
        }
        
    }
}
