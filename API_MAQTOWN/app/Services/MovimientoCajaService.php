<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class MovimientoCajaService
{
    public function ServAbreMovimientoCaja($idUsuario, $request){

        return DB::select('CALL sp_abreMovimientoCaja(?, ?, ?)', [
            $idUsuario,
            $request['cajas_idcajas'],
            $request['monto_apertura']
        ]);
    }

    public function ServCierraMovimientoCaja($idUsuario, $request){

        return DB::transaction(function () use ($idUsuario, $request) {

            // 1. Arqueo opcional: se registra cada denominación contada
            $arqueo = [];
            foreach (($request['arqueo'] ?? []) as $fila) {
                $resultadoArqueo = DB::select('CALL sp_registroDetalleArqueo(?, ?, ?)', [
                    $request['idmovimiento_caja'],
                    $fila['valor_denominacion'],
                    $fila['cantidad']
                ]);

                $arqueo[] = $resultadoArqueo[0];
            }

            // 2. Cierre: valida usuario, estado y coincidencia del arqueo
            $cierre = DB::select('CALL sp_cierraMovimientoCaja(?, ?, ?)', [
                $idUsuario,
                $request['idmovimiento_caja'],
                $request['monto_cierre_declarado']
            ]);

            return [
                'cierre' => $cierre[0],
                'arqueo' => $arqueo
            ];
        });
    }

    public function ServResumenMovimientoCaja($id){

        $resultado = DB::select('CALL sp_resumenMovimientoCaja(?)', [$id]);

        return $resultado[0] ?? null;
    }

    public function ServListaMovimientoCaja($idSucursal, $idCaja, $estado, $fechaInicio, $fechaFin){

        return DB::select('CALL sp_listaMovimientoCaja(?, ?, ?, ?, ?)', [
            $idSucursal,
            $idCaja,
            $estado,
            $fechaInicio,
            $fechaFin
        ]);
    }

    public function ServSesionAbiertaUsuario($idUsuario){

        $resultado = DB::select('CALL sp_sesionAbiertaUsuario(?)', [$idUsuario]);

        return $resultado[0] ?? null;
    }
}