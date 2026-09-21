<?php

namespace App\Services;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PresentacionService
{
    public function __construct()
    {
        //
    }

    public function ServListaPresentacion()
    {

        // Lógica para listar presentacion
         $resultados = DB::select('CALL sp_listaPresentacion()');

        return $resultados;
    }

    public function ServRegistraPresentacion($idUsuario, $request)
    {
        // Lógica para guardar un presentacion
         $resultados = DB::select('CALL sp_registroPresentacion(?,?,?,?)', [
            $idUsuario,
            $request['nombre_presentacion'],
            $request['abreviatura'],
            $request['descripcion']
         ]);

         return $resultados;
    }

    public function ServEditaPresentacion($idUsuario, $id, $request)
    {
        // Lógica para actualizar
        $parametros = [
        'nombre_presentacion' => null,
        'abreviatura'         => null,
        'descripcion'         => null,
    ];

    $datosValidos = array_intersect_key($request, array_flip([
        'nombre_presentacion', 'abreviatura', 'descripcion'
    ]));

    $parametros['nombre_presentacion'] = $datosValidos['nombre_presentacion'] ?? null;
    $parametros['abreviatura']         = $datosValidos['abreviatura'] ?? null;
    $parametros['descripcion']         = $datosValidos['descripcion'] ?? null;

    return DB::select('CALL sp_actualizaPresentacion(?, ?, ?, ?, ?)', [
        $idUsuario,
        $id,
        $parametros['nombre_presentacion'],
        $parametros['abreviatura'],
        $parametros['descripcion']
    ]);

    }

    public function ServEliminaPresentacion($id)
    {
        // Lógica para eliminar
         $resultados = DB::select('CALL sp_eliminaPresentacion(?)', [$id]);

        return $resultados;
    }
}