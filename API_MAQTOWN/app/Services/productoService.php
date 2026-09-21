<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class ProductoService
{
    public function ServVerificaProducto($codigoProductoProveedor){
        return DB::select('CALL sp_verificaExisteProducto(?)', [$codigoProductoProveedor]);
    }

    public function ServRegistraProducto($idUsuario, $request){

        // 1. Verificamos si ya existe un producto con ese codigo_producto_proveedor
        $existe = $this->ServVerificaProducto($request['codigo_producto_proveedor']);

        if (count($existe) > 0) {
            return [
                'existe' => true,
                'data'   => $existe
            ];
        }

        // 2. Si no existe, procedemos a registrar
        $resultados = DB::select('CALL sp_registroProducto(?,?,?,?,?,?,?,?,?,?,?)', [
            $idUsuario,
            $request['codigo_barras'],
            $request['codigo_producto_proveedor'],
            $request['nombre'],
            $request['descripcion_corta'],
            $request['descripcion_larga'],
            $request['marca'],
            $request['imagen_url'],
            $request['categoria_idcategorias'],
            $request['presentacion_idpresentacion'],
            $request['proveedores_idproveedores']
        ]);

        return [
            'existe' => false,
            'data'   => $resultados
        ];
    }

    public function ServListaProducto(){

        $resultados = DB::select('CALL sp_listaProducto()');

        return $resultados;
    }

    public function ServEditaProducto($idUsuario, $id, $request){

        $parametros = [
            'codigo_barras'                => null,
            'codigo_producto_proveedor'    => null,
            'nombre'                       => null,
            'descripcion_corta'            => null,
            'descripcion_larga'            => null,
            'marca'                        => null,
            'imagen_url'                   => null,
            'activo_catalogo'              => null,
            'categoria_idcategorias'       => null,
            'presentacion_idpresentacion'  => null,
            'proveedores_idproveedores'    => null,
        ];

        $datosValidos = array_intersect_key($request, array_flip(array_keys($parametros)));

        foreach ($parametros as $key => $value) {
            $parametros[$key] = $datosValidos[$key] ?? null;
        }

        return DB::select('CALL sp_actualizaProducto(?,?,?,?,?,?,?,?,?,?,?,?,?)', [
            $idUsuario,
            $id,
            $parametros['codigo_barras'],
            $parametros['codigo_producto_proveedor'],
            $parametros['nombre'],
            $parametros['descripcion_corta'],
            $parametros['descripcion_larga'],
            $parametros['marca'],
            $parametros['imagen_url'],
            $parametros['activo_catalogo'],
            $parametros['categoria_idcategorias'],
            $parametros['presentacion_idpresentacion'],
            $parametros['proveedores_idproveedores']
        ]);
    }

    public function ServEliminaProducto($id){

        $resultados = DB::select('CALL sp_eliminaProducto(?)', [$id]);

        return $resultados;
    }
    public function ServActivarProducto($codigoProductoProveedor){
        return DB::select('CALL sp_activaProducto(?)', [$codigoProductoProveedor]);
    }
    // buscador por texto
    public function ServBusquedaTextoProducto($texto){
        return DB::select('CALL sp_buscaProductoTexto(?)', [$texto]);
    }

}