<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class SucursalService
{
    
    public function ServVerificaSucursal($nombre){
    return DB::select('CALL sp_verificaExisteSucursal(?)', [$nombre]);
    }

    public function ServRegistraSucursal($idUsuario, $request){

        // 1. Verificamos si ya existe una sucursal con ese nombre
        $existe = $this->ServVerificaSucursal($request['nombre']);

        if (count($existe) > 0) {
            return [
                'existe' => true,
                'data'   => $existe
            ];
        }

        // 2. Si no existe, procedemos a registrar
        $resultados = DB::select('CALL sp_registroSucursal(?,?,?,?,?)', [
            $idUsuario,
            $request['nombre'],
            $request['direccion'],
            $request['ciudad'],
            $request['celular']
        ]);

        return [
            'existe' => false,
            'data'   => $resultados
        ];
    }

    public function ServListaSucursal(){

        $resultados = DB::select('CALL sp_listaSucursal()');

        return $resultados;
    }

    public function ServEditaSucursal($idUsuario, $id, $request){

        $parametros = [
            'nombre'    => null,
            'direccion' => null,
            'ciudad'    => null,
            'celular'   => null,
        ];

        $datosValidos = array_intersect_key($request, array_flip([
            'nombre', 'direccion', 'ciudad', 'celular'
        ]));

        $parametros['nombre']    = $datosValidos['nombre'] ?? null;
        $parametros['direccion'] = $datosValidos['direccion'] ?? null;
        $parametros['ciudad']    = $datosValidos['ciudad'] ?? null;
        $parametros['celular']   = $datosValidos['celular'] ?? null;

        return DB::select('CALL sp_actualizaSucursal(?, ?, ?, ?, ?, ?)', [
            $idUsuario,
            $id,
            $parametros['nombre'],
            $parametros['direccion'],
            $parametros['ciudad'],
            $parametros['celular']
        ]);
    }

    public function ServEliminaSucursal($id){

        $resultados = DB::select('CALL sp_eliminaSucursal(?)', [$id]);

        return $resultados;
    }
}