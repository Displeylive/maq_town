<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class CompraService
{
    public function ServRegistraCompra($idUsuario, $request){

        return DB::transaction(function () use ($idUsuario, $request) {

            // 1. Crear la cabecera de la compra
            $cabecera = DB::select('CALL sp_registroCompra(?,?,?,?,?,?)', [
                $idUsuario,
                $request['numero_factura_prov'],
                $request['estado_pago'],
                $request['fecha_limite_pago'],
                $request['proveedores_idproveedores'],
                $request['sucursales_idsucursales']
            ]);

            $idCompra = $cabecera[0]->idcompras;

            // 2. Insertar cada línea del detalle
            $detalles = [];
            foreach ($request['detalle'] as $producto) {
                $resultadoDetalle = DB::select('CALL sp_registroCompraDetalle(?,?,?,?,?)', [
                    $idUsuario,
                    $idCompra,
                    $producto['productos_idproductos'],
                    $producto['cantidad'],
                    $producto['costo_unitario']
                ]);

                $detalles[] = $resultadoDetalle[0];
            }

            // 3. Recalcular el total de la compra
            $totalActualizado = DB::select('CALL sp_actualizaTotalCompra(?)', [$idCompra]);

            return [
                'compra'   => $totalActualizado[0],
                'detalles' => $detalles
            ];
        });
    }

    public function ServListaCompra(){
        return DB::select('CALL sp_listaCompra()');
    }

    public function ServEditaCompra($idUsuario, $id, $request){

        $parametros = [
            'numero_factura_prov' => null,
            'total_compra'        => null,
            'fecha_compra'        => null,
            'estado_pago'         => null,
            'fecha_limite_pago'   => null,
        ];

        $datosValidos = array_intersect_key($request, array_flip(array_keys($parametros)));

        foreach ($parametros as $key => $value) {
            $parametros[$key] = $datosValidos[$key] ?? null;
        }

        return DB::select('CALL sp_actualizaCompra(?, ?, ?, ?, ?, ?, ?)', [
            $idUsuario,
            $id,
            $parametros['numero_factura_prov'],
            $parametros['total_compra'],
            $parametros['fecha_compra'],
            $parametros['estado_pago'],
            $parametros['fecha_limite_pago']
        ]);
    }

    public function ServBuscarIdCompra($id){

        $cabecera = DB::select('CALL sp_buscaCompraPorId(?)', [$id]);
        $detalle  = DB::select('CALL sp_listaCompraDetallePorCompra(?)', [$id]);

        return [
            'compra'   => $cabecera[0] ?? null,
            'detalle'  => $detalle
        ];
    }

    public function ServEliminaCompra($idUsuario, $id){
        return DB::select('CALL sp_eliminaCompra(?, ?)', [$idUsuario, $id]);
    }


}