<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class DetalleVentaService
{
    public function ServAgregaDetalleVenta($idUsuario, $request){

        return DB::transaction(function () use ($idUsuario, $request) {

            $totalAnterior = $this->obtenerTotalActual($request['idventas']);

            $detalle = DB::select('CALL sp_registroDetalleVenta(?,?,?,?,?,?)', [
                $idUsuario,
                $request['idventas'],
                $request['productos_idproductos'],
                $request['cantidad'],
                $request['precio_venta_producto'],
                $request['descuento'] ?? 0
            ]);

            DB::select('CALL sp_actualizaTotalVenta(?)', [$request['idventas']]);

            $ventaAjustada = DB::select('CALL sp_ajustaSaldoClientePorVenta(?, ?)', [
                $request['idventas'],
                $totalAnterior
            ]);

            return [
                'detalle' => $detalle[0],
                'venta'   => $ventaAjustada[0]
            ];
        });
    }

    public function ServEditaDetalleVenta($idUsuario, $id, $request){

        return DB::transaction(function () use ($idUsuario, $id, $request) {

            $idVenta = DB::table('detalle_venta')->where('iddetalle_venta', $id)->value('ventas_idventas');
            $totalAnterior = $this->obtenerTotalActual($idVenta);

            $parametros = [
                'cantidad'               => null,
                'precio_venta_producto'  => null,
                'descuento'              => null,
            ];

            $datosValidos = array_intersect_key($request, array_flip(array_keys($parametros)));

            foreach ($parametros as $key => $value) {
                $parametros[$key] = $datosValidos[$key] ?? null;
            }

            $detalle = DB::select('CALL sp_actualizaDetalleVenta(?, ?, ?, ?, ?)', [
                $idUsuario,
                $id,
                $parametros['cantidad'],
                $parametros['precio_venta_producto'],
                $parametros['descuento']
            ]);

            DB::select('CALL sp_actualizaTotalVenta(?)', [$idVenta]);

            $ventaAjustada = DB::select('CALL sp_ajustaSaldoClientePorVenta(?, ?)', [
                $idVenta,
                $totalAnterior
            ]);

            return [
                'detalle' => $detalle[0],
                'venta'   => $ventaAjustada[0]
            ];
        });
    }

    public function ServEliminaDetalleVenta($idUsuario, $id){

        return DB::transaction(function () use ($idUsuario, $id) {

            $idVenta = DB::table('detalle_venta')->where('iddetalle_venta', $id)->value('ventas_idventas');
            $totalAnterior = $this->obtenerTotalActual($idVenta);

            DB::select('CALL sp_eliminaDetalleVenta(?, ?)', [$idUsuario, $id]);

            DB::select('CALL sp_actualizaTotalVenta(?)', [$idVenta]);

            $ventaAjustada = DB::select('CALL sp_ajustaSaldoClientePorVenta(?, ?)', [
                $idVenta,
                $totalAnterior
            ]);

            return [
                'venta' => $ventaAjustada[0]
            ];
        });
    }

    private function obtenerTotalActual($idVenta){
        return DB::table('ventas')->where('idventas', $idVenta)->value('pago_total');
    }
}