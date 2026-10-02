<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class CajaService
{
    public function ServVerificaCaja($nombreCaja, $idSucursal){
        return DB::select('CALL sp_verificaExisteCaja(?, ?)', [$nombreCaja, $idSucursal]);
    }

    public function ServRegistraCaja($idUsuario, $request){

        $existe = $this->ServVerificaCaja($request['nombre_caja'], $request['sucursales_idsucursales']);

        if (count($existe) > 0) {
            return [
                'existe' => true,
                'data'   => $existe
            ];
        }

        $resultados = DB::select('CALL sp_registroCaja(?,?,?,?)', [
            $idUsuario,
            $request['nombre_caja'],
            $request['descripcion'] ?? null,
            $request['sucursales_idsucursales']
        ]);

        return [
            'existe' => false,
            'data'   => $resultados
        ];
    }

    public function ServListaCaja($idSucursal = null, $estado = null){
        return DB::select('CALL sp_listaCaja(?, ?)', [$idSucursal, $estado]);
    }

    public function ServEditaCaja($idUsuario, $id, $request){

        $parametros = [
            'nombre_caja' => null,
            'descripcion' => null,
        ];

        $datosValidos = array_intersect_key($request, array_flip(array_keys($parametros)));

        foreach ($parametros as $key => $value) {
            $parametros[$key] = $datosValidos[$key] ?? null;
        }

        return DB::select('CALL sp_actualizaCaja(?, ?, ?, ?)', [
            $idUsuario,
            $id,
            $parametros['nombre_caja'],
            $parametros['descripcion']
        ]);
    }

    public function ServDesactivaCaja($idUsuario, $id){
        return DB::select('CALL sp_desactivaCaja(?, ?)', [$idUsuario, $id]);
    }

    public function ServActivaCaja($idUsuario, $id){
        return DB::select('CALL sp_activaCaja(?, ?)', [$idUsuario, $id]);
    }
}