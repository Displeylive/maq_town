<?php

namespace App\Http\Controllers;
use App\Services\ProveedorService;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{

        public function __construct( protected  ProveedorService $service){

         }

        public function registraProveedor(Request $request){
            try {
                //usuario en session
                $Objeto=$request->user();
                // ✅ Un solo validate con todas las reglas
                $datos = $request->validate([
                   
                    'razon_social'            => 'string|max:150',
                    'nit_rut'                      => 'numeric',
                    'direccion'                => 'string|max:50'
                ]);
                // precesa registro de cliente
             $resultado = $this->service->ServRegistraProveedor($Objeto->idusuario , $datos);
                return response()->json([
                    'estado'  => 'success',
                    'mensaje' => 'Proveedor registrado correctamente',
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

        public function listaProveedor(){
        try {
             $resultado = $this->service->ServListaProveedor();
                return response()->json([
                    'estado'  => 'success',
                    'mensaje' => 'Lista de proveedores',
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

        public function editaProveedor(Request $request){
            try {
                //usuario en session
                $Objeto=$request->user();
                // ✅ Un solo validate con todas las reglas
                $datos = $request->validate([
                    'idProveedor'                => 'integer',
                    'razon_social'                => 'string|max:150',
                    'nit_rut'                      => 'integer',
                    'direccion'                    => 'string|max:50'
                ]);
                // precesa registro de cliente
             $resultado = $this->service->ServEditaProveedor($Objeto->idusuario , $datos);
                return response()->json([
                    'estado'  => 'success',
                    'mensaje' => 'Se actualizo el registro correctamente',
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
        
        public function eliminaProveedor(Request $request){
            try{ 

             $Objeto=$request->user();
              $datos = $request->validate([
                    'idProveedor'                   => 'integer|required'
                     ]);
              $resultado = $this->service->ServEliminaProveedor($Objeto->idusuario ,$datos);
                return response()->json([
                    'estado'  => 'success',
                    'mensaje' => 'Proveedor eliminado correctamente ',
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

}
