<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class CompraDetalleService
{
    public function ServAgregaCompraDetalle($idUsuario, $request){

        return DB::transaction(function () use ($idUsuario, $request) {

            $detalle = DB::select('CALL sp_registroCompraDetalle(?,?,?,?,?)', [
                $idUsuario,
                $request['idcompras'],
                $request['productos_idproductos'],
                $request['cantidad'],
                $request['costo_unitario']
            ]);

            $totalActualizado = DB::select('CALL sp_actualizaTotalCompra(?)', [$request['idcompras']]);

            return [
                'detalle' => $detalle[0],
                'compra'  => $totalActualizado[0]
            ];
        });
    }

    public function ServEditaCompraDetalle($idUsuario, $id, $request){

        return DB::transaction(function () use ($idUsuario, $id, $request) {

            $parametros = [
                'cantidad'       => null,
                'costo_unitario' => null,
            ];

            $datosValidos = array_intersect_key($request, array_flip(array_keys($parametros)));

            foreach ($parametros as $key => $value) {
                $parametros[$key] = $datosValidos[$key] ?? null;
            }

            $detalle = DB::select('CALL sp_actualizaCompraDetalle(?, ?, ?, ?)', [
                $idUsuario,
                $id,
                $parametros['cantidad'],
                $parametros['costo_unitario']
            ]);

            $idCompra = $detalle[0]->compras_idcompras;

            $totalActualizado = DB::select('CALL sp_actualizaTotalCompra(?)', [$idCompra]);

            return [
                'detalle' => $detalle[0],
                'compra'  => $totalActualizado[0]
            ];
        });
    }

    public function ServEliminaCompraDetalle($idUsuario, $id){

        return DB::transaction(function () use ($idUsuario, $id) {

            $resultado = DB::select('CALL sp_eliminaCompraDetalle(?, ?)', [$idUsuario, $id]);

            $idCompra = $resultado[0]->idcompras;

            $totalActualizado = DB::select('CALL sp_actualizaTotalCompra(?)', [$idCompra]);

            return [
                'compra' => $totalActualizado[0]
            ];
        });
    }
}