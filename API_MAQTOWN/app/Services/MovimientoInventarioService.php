<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class MovimientoInventarioService
{
    public function ServListaMovimientoInventario($idproducto, $fechaInicio, $fechaFin){

        return DB::select('CALL sp_listaMovimientoInventario(?, ?, ?)', [
            $idproducto,
            $fechaInicio,
            $fechaFin
        ]);
    }
}