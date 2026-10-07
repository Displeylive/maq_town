<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class DetalleArqueoService
{
    public function ServListaDetalleArqueo($idMovimientoCaja){

        $filas = DB::select('CALL sp_listaDetalleArqueo(?)', [$idMovimientoCaja]);

        $total = 0;
        foreach ($filas as $fila) {
            $total += (float) $fila->sub_total;
        }

        return [
            'arqueo' => $filas,
            'total'  => round($total, 2)
        ];
    }
}