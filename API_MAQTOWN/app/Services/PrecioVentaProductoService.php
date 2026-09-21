<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class PrecioVentaProductoService
{
      // REGISTRAR
    public function ServRegistraPrecioVentaProducto($idUsuario, $request){

        $resultados = DB::select('CALL sp_registroPrecioVentaProducto(?,?,?,?,?,?)', [
            $idUsuario,
            $request['tipo_cliente'],
            $request['precio_venta'],
            $request['cantidad_minima'],
            $request['limite_descuento'],
            $request['productos_idproductos']
        ]);

        return $resultados;
    }
     // LISTAR
    public function ServListaPrecioVentaProducto(){

        return DB::select('CALL sp_listaPrecioVentaProducto()');
    }
     // EDITAR
    public function ServEditaPrecioVentaProducto($idUsuario, $id, $request){

        $parametros = [
            'tipo_cliente'      => null,
            'precio_venta'      => null,
            'cantidad_minima'   => null,
            'limite_descuento'  => null,
        ];

        $datosValidos = array_intersect_key($request, array_flip(array_keys($parametros)));

        foreach ($parametros as $key => $value) {
            $parametros[$key] = $datosValidos[$key] ?? null;
        }

        return DB::select('CALL sp_actualizaPrecioVentaProducto(?, ?, ?, ?, ?, ?)', [
            $idUsuario,
            $id,
            $parametros['tipo_cliente'],
            $parametros['precio_venta'],
            $parametros['cantidad_minima'],
            $parametros['limite_descuento']
        ]);
    }
     // ELIMINAR
    public function ServEliminaPrecioVentaProducto($id){

        return DB::select('CALL sp_eliminaPrecioVentaProducto(?)', [$id]);
    }
}