<?php

namespace App\Services;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProveedorService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function ServRegistraProveedor($id , $request){
          return DB:: select('CALL sp_registroProveedor(?,?,?,?)',[$id, $request['razon_social'], $request['nit_rut'], $request['direccion']]);

        }

        public function ServListaProveedor(){
         return  DB:: select('CALL sp_listaProveedor()');
             
        }

        public function ServEditaProveedor($id, $request){
   // 1. Definimos la estructura completa con valores por defecto (null)
            $parametros = [
                'idProveedor' => null,
                'razon_social' => null,
                'nit_rut'    => null,
                'direccion'   => null       
            ];

            // 2. Mapeamos los datos recibidos a los parámetros del SP
            // Usamos intersect_key para asegurar que solo pasamos campos válidos
            $datosValidos = array_intersect_key($request, array_flip([
                'idProveedor', 'razon_social', 'nit_rut', 'direccion'
            ]));

            // 3. Sobrescribimos el mapa original
            // Esto asegura que si algo no vino en $request, se quede como null
            $parametros['idProveedor']             = $datosValidos['idProveedor'] ?? null;
            $parametros['razon_social']             = $datosValidos['razon_social'] ?? null;
            $parametros['nit_rut']                   = $datosValidos['nit_rut'] ?? null;
            $parametros['direccion']                 = $datosValidos['direccion'] ?? null;

            // 4. Ejecutamos el SP enviando el array de valores de forma plana
            return DB::select('CALL sp_actualizaProveedor( ?, ?, ?, ?, ?)',[$id, $parametros['idProveedor'],  $parametros['razon_social'], $parametros['nit_rut'], $parametros['direccion'] ]);
        }
        
        public function ServEliminaProveedor( $id, $request){
            return DB:: select('CALL sp_eliminaProveedor(?,?)', [$id, $request['idProveedor']]);
        }
}
