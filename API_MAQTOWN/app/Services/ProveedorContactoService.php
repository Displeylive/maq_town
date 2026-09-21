<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class ProveedorContactoService
{
    public function ServVerificaProveedorContacto($contactoNombre, $idProveedor){
        return DB::select('CALL sp_verificaExisteProveedorContacto(?, ?)', [$contactoNombre, $idProveedor]);
    }

    public function ServRegistraProveedorContacto($idUsuario, $request){

        $existe = $this->ServVerificaProveedorContacto($request['contacto_nombre'], $request['proveedores_idproveedores']);

        if (count($existe) > 0) {
            return [
                'existe' => true,
                'data'   => $existe
            ];
        }

        $resultados = DB::select('CALL sp_registroProveedorContacto(?,?,?,?,?)', [
            $idUsuario,
            $request['contacto_nombre'],
            $request['email'],
            $request['telefono'],
            $request['proveedores_idproveedores']
        ]);

        return [
            'existe' => false,
            'data'   => $resultados
        ];
    }

    public function ServListaProveedorContacto($idProveedor = null){
        return DB::select('CALL sp_listaProveedorContacto(?)', [$idProveedor]);
    }

    public function ServEditaProveedorContacto($idUsuario, $id, $request){

        $parametros = [
            'contacto_nombre' => null,
            'email'           => null,
            'telefono'        => null,
        ];

        $datosValidos = array_intersect_key($request, array_flip(array_keys($parametros)));

        foreach ($parametros as $key => $value) {
            $parametros[$key] = $datosValidos[$key] ?? null;
        }

        return DB::select('CALL sp_actualizaProveedorContacto(?, ?, ?, ?, ?)', [
            $idUsuario,
            $id,
            $parametros['contacto_nombre'],
            $parametros['email'],
            $parametros['telefono']
        ]);
    }

    public function ServEliminaProveedorContacto($id){
        return DB::select('CALL sp_eliminaProveedorContacto(?)', [$id]);
    }
}