<?php

namespace App\Http\Controllers;
use App\Services\ClienteService;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    // coloca los metodos del cliente service       
        public function __construct(
        protected ClienteService $service
        ) {}

      public function registraCliente(Request $request){
        try {
                //usuario en session
                $Objeto=$request->user();
                // ✅ Un solo validate con todas las reglas
                $datos = $request->validate([
                    'nombreRazonSocial'            => 'string|max:150',
                    'nit_rut'                      => 'integer',
                    'tipo_cliente'                => 'string|max:50'
                ]);
                // precesa registro de cliente
             $resultado = $this->service->registraCliente($Objeto->idusuario , $datos);
                return response()->json([
                    'estado'  => 'success',
                    'mensaje' => 'cliente registrado correctamente',
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

        public function listaCLiente(){
  try {
             $resultado = $this->service->listaCLiente();
                return response()->json([
                    'estado'  => 'success',
                    'mensaje' => 'Lista de clientes',
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

        public function editaCliente(Request $request){
         try {
                //usuario en session
                $Objeto=$request->user();
                // ✅ Un solo validate con todas las reglas
                $datos = $request->validate([
                    'idclientes'                   => 'integer|required',
                    'nombre_razon_social'          => 'string|max:150',
                    'nit'                          => 'integer',
                    'tipo_cliente'                 => 'string|max:50',
                    'limite_credito'               => 'numeric',
                    'saldo_actual'                 => 'numeric'
                ]);
                // precesa registro de cliente
             $resultado = $this->service->editaCliente($Objeto->idusuario , $datos);
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
        
        public function eliminacliente(Request $request){
            try{ 

             $Objeto=$request->user();
              $datos = $request->validate([
                    'idclientes'                   => 'integer|required'
                     ]);
              $resultado = $this->service->eliminacliente($Objeto->idusuario ,$datos);
                return response()->json([
                    'estado'  => 'success',
                    'mensaje' => 'Cliente eliminado correctamente ',
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
