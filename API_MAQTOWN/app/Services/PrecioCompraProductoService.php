<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class PrecioCompraProductoService
{
    public function ServListaPrecioCompraProducto($idproducto, $fechaInicio, $fechaFin){

        return DB::select('CALL sp_listaPrecioCompraProducto(?, ?, ?)', [
            $idproducto,
            $fechaInicio,
            $fechaFin
        ]);
    }
}