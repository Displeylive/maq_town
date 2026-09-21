<?php

namespace App\Services;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class categoriaService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

        public function ServRegistraCategoria($id , $request){
       $resultados=DB:: select('CALL sp_registroCategoria(?,?,?,?,?)',[$id, $request['nombre'], $request['slug'], $request['imagen_url'] ,$request['descripcion']]);
        
       return $resultados;
       }
                

        public function ServListaCategoria(){
            $resultados=DB:: select('CALL sp_listaCategoria()');
              return $resultados;
        }

        public function ServEditaCategoria($idUsuario, $id, $request){

            // 1. Definimos la estructura completa con valores por defecto (null)
            $parametros = [
                'nombre'      => null,
                'slug'        => null,
                'imagen_url'  => null,
                'descripcion' => null,
            ];

            // 2. Mapeamos los datos recibidos a los parámetros del SP
            $datosValidos = array_intersect_key($request, array_flip([
                'nombre', 'slug', 'imagen_url', 'descripcion'
            ]));

            // 3. Sobrescribimos el mapa original
            $parametros['nombre']      = $datosValidos['nombre'] ?? null;
            $parametros['slug']        = $datosValidos['slug'] ?? null;
            $parametros['imagen_url']  = $datosValidos['imagen_url'] ?? null;
            $parametros['descripcion'] = $datosValidos['descripcion'] ?? null;

            // 4. Ejecutamos el SP enviando el array de valores de forma plana
            return DB::select('CALL sp_actualizaCategoria(?, ?, ?, ?, ?, ?)', [
                $idUsuario,
                $id,
                $parametros['nombre'],
                $parametros['slug'],
                $parametros['imagen_url'],
                $parametros['descripcion']
            ]);
        }
        public function ServEliminaCategoria($id){

            $resultados = DB::select('CALL sp_EliminaCategoria(?)', [$id]);

            return $resultados;
        }
}
