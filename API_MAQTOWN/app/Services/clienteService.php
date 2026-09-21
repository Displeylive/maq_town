<?php

namespace App\Services;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ClienteService
{
    /**
     * Create a new class instance.
     *  $resultados=DB:: select('CALL sp_permisos(?)'[$request->idusuario]);
        return $resultados;
     */
    public function __construct()
    {
        //
    }

         public function registraCliente($id , $request){
         $resultados=DB:: select('CALL sp_registroCliente(?,?,?,?)',[$id, $request['nombreRazonSocial'], $request['nit_rut'], $request['tipo_cliente']]);
    //   $resultados=DB:: select('CALL sp_registroCliente(?,?,?,?)',[$id, $request->nombreRazonSocial, $request->nit_rut, $request->tipo_cliente]);
       return $resultados;
       }
               

        public function listaCLiente(){
             $resultados=DB:: select('CALL sp_listaClientes()');
              return $resultados;
        }

        public function editaCliente($id, $request){
            
            // 1. Definimos la estructura completa con valores por defecto (null)
            $parametros = [
                'idclientes' => null,
                'nombre_razon_social' => null,
                'nit'    => null,
                'tipo_cliente'   => null,
                'limite_credito'  => null,
                'saldo_actual'  => null         
            ];

            // 2. Mapeamos los datos recibidos a los parámetros del SP
            // Usamos intersect_key para asegurar que solo pasamos campos válidos
            $datosValidos = array_intersect_key($request, array_flip([
                'idclientes', 'nombre_razon_social', 'nit', 'tipo_cliente','limite_credito','saldo_actual'
            ]));

            // 3. Sobrescribimos el mapa original
            // Esto asegura que si algo no vino en $request, se quede como null
            $parametros['idclientes']             = $datosValidos['idclientes'] ?? null;
            $parametros['nombre_razon_social']    = $datosValidos['nombre_razon_social'] ?? null;
            $parametros['nit']                    = $datosValidos['nit'] ?? null;
            $parametros['tipo_cliente']           = $datosValidos['tipo_cliente'] ?? null;
            $parametros['limite_credito']         = $datosValidos['limite_credito'] ?? null;
            $parametros['saldo_actual']           = $datosValidos['saldo_actual'] ?? null;

            // 4. Ejecutamos el SP enviando el array de valores de forma plana
            return DB::select('CALL sp_actualizaCliente( ?, ?, ?, ?, ?, ?,?)',[$id, $parametros['idclientes'],  $parametros['nombre_razon_social'], $parametros['nit'], $parametros['tipo_cliente'],  $parametros['limite_credito'],$parametros['saldo_actual'] ]);
        }
        
        public function eliminacliente( $id, $request){
          return DB:: select('CALL sp_eliminaCliente(?,?)', [$id, $request['idclientes']]);
        }
}
