<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class DetalleMovimientoCajaService
{
    public function ServRegistraGasto($idUsuario, $request){

        return DB::transaction(function () use ($idUsuario, $request) {

            $resultado = DB::select('CALL sp_registroGasto(?, ?, ?, ?, ?)', [
                $idUsuario,
                $request['idmovimiento_caja'],
                $request['monto'],
                $request['metodo_pago'],
                $request['comentario']
            ]);

            return $resultado[0];
        });
    }

    public function ServEditaGasto($idUsuario, $id, $request){

        return DB::transaction(function () use ($idUsuario, $id, $request) {

            $parametros = [
                'monto'       => null,
                'metodo_pago' => null,
                'comentario'  => null,
            ];

            $datosValidos = array_intersect_key($request, array_flip(array_keys($parametros)));

            foreach ($parametros as $key => $value) {
                $parametros[$key] = $datosValidos[$key] ?? null;
            }

            $resultado = DB::select('CALL sp_actualizaGasto(?, ?, ?, ?, ?)', [
                $idUsuario,
                $id,
                $parametros['monto'],
                $parametros['metodo_pago'],
                $parametros['comentario']
            ]);

            return $resultado[0];
        });
    }

    public function ServEliminaGasto($idUsuario, $id){

        return DB::transaction(function () use ($idUsuario, $id) {

            $resultado = DB::select('CALL sp_eliminaGasto(?, ?)', [$idUsuario, $id]);

            return $resultado[0];
        });
    }

    public function ServListaDetalleMovimientoCaja($idMovimientoCaja, $tipoMovimiento = null){

        return DB::select('CALL sp_listaDetalleMovimientoCaja(?, ?)', [
            $idMovimientoCaja,
            $tipoMovimiento
        ]);
    }
}