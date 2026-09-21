<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class VentaService
{
    public function ServRegistraVenta($idUsuario, $request){

        return DB::transaction(function () use ($idUsuario, $request) {

            // 1. Crear la cabecera de la venta
            $cabecera = DB::select('CALL sp_registroVenta(?,?,?,?,?,?,?,?)', [
                $idUsuario,
                $request['tipo_venta'],
                $request['tipo_pago'],
                $request['metodo_pago'],
                $request['metodo_entrega'],
                $request['estado_venta'],
                $request['sucursales_idsucursales'],
                $request['clientes_idclientes']
            ]);

            $idVenta = $cabecera[0]->idventas;

            // 2. Insertar cada línea del detalle (valida stock y descuenta)
            $detalles = [];
            foreach ($request['productos'] as $producto) {
                $resultadoDetalle = DB::select('CALL sp_registroDetalleVenta(?,?,?,?,?,?)', [
                    $idUsuario,
                    $idVenta,
                    $producto['productos_idproductos'],
                    $producto['cantidad'],
                    $producto['precio_venta_producto'],
                    $producto['descuento'] ?? 0
                ]);

                $detalles[] = $resultadoDetalle[0];
            }

            // 3. Finalizar: recalcula el total y valida crédito si aplica
            $ventaFinalizada = DB::select('CALL sp_finalizaVenta(?)', [$idVenta]);

            return [
                'venta'    => $ventaFinalizada[0],
                'detalles' => $detalles
            ];
        });
    }


    public function ServListaVenta(){
        return DB::select('CALL sp_listaVenta()');
    }

    public function ServListaVentaAnulada(){
        return DB::select('CALL sp_listaVentaAnulada()');
    }

    public function ServBuscarIdVenta($id){
        $cabecera = DB::select('CALL sp_buscaVentaPorId(?)', [$id]);
        $detalle  = DB::select('CALL sp_listaDetalleVentaPorVenta(?)', [$id]);

        return [
            'venta'   => $cabecera[0] ?? null,
            'detalle' => $detalle
        ];
    }

    public function ServEditaVenta($idUsuario, $id, $request){

        $parametros = [
            'tipo_venta'      => null,
            'metodo_pago'     => null,
            'metodo_entrega'  => null,
            'estado_venta'    => null,
        ];

        $datosValidos = array_intersect_key($request, array_flip(array_keys($parametros)));

        foreach ($parametros as $key => $value) {
            $parametros[$key] = $datosValidos[$key] ?? null;
        }

        return DB::select('CALL sp_actualizaVenta(?, ?, ?, ?, ?, ?)', [
            $idUsuario,
            $id,
            $parametros['tipo_venta'],
            $parametros['metodo_pago'],
            $parametros['metodo_entrega'],
            $parametros['estado_venta']
        ]);
    }

    public function ServAnulaVenta($idUsuario, $id){
        return DB::select('CALL sp_anulaVenta(?, ?)', [$idUsuario, $id]);
    }

}