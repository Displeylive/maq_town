<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class InventarioSucursalService
{
    public function ServListaInventarioSucursal($nombreSucursal = null){

        $resultados = DB::select('CALL sp_listaInventarioSucursal(?)', [$nombreSucursal]);

        return $resultados;
    }

    public function ServEditaInventarioSucursal($idUsuario, $id, $request){

        $parametros = [
            'Stock_minino'      => null,
            'ubicacion_pasillo' => null,
        ];

        $datosValidos = array_intersect_key($request, array_flip([
            'Stock_minino', 'ubicacion_pasillo'
        ]));

        $parametros['Stock_minino']      = $datosValidos['Stock_minino'] ?? null;
        $parametros['ubicacion_pasillo'] = $datosValidos['ubicacion_pasillo'] ?? null;

        return DB::select('CALL sp_actualizaInventarioSucursal(?, ?, ?, ?)', [
            $idUsuario,
            $id,
            $parametros['Stock_minino'],
            $parametros['ubicacion_pasillo']
        ]);
    }
}