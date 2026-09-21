<?php

//use Illuminate\Database\Eloquent\Model;
//use Illuminate\Support\Facades\DB;

namespace App\Http\Controllers;

use App\Services\ProductoService;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    protected $service;

    public function __construct(ProductoService $service)
    {
        $this->service = $service;
    }

    public function RegistraProducto(Request $request){
        try {
            $Objeto = $request->user();

            $datos = $request->validate([
                'codigo_barras'                => 'required|string|max:50',
                'codigo_producto_proveedor'    => 'required|string|max:45',
                'nombre'                       => 'required|string|max:150',
                'descripcion_corta'            => 'required|string|max:255',
                'descripcion_larga'            => 'required|string|max:255',
                'marca'                        => 'required|string|max:45',
                'imagen_url'                   => 'required|string|max:255',
                'categoria_idcategorias'       => 'required|integer',
                'presentacion_idpresentacion'  => 'required|integer',
                'proveedores_idproveedores'    => 'required|integer',
            ]);

            $resultado = $this->service->ServRegistraProducto($Objeto->idusuario, $datos);

            if ($resultado['existe']) {
                return response()->json([
                    'estado'  => 'error',
                    'mensaje' => 'El producto ya existe',
                    'data'    => $resultado['data']
                ], 409);
            }

            return response()->json([
                'estado'  => 'success',
                'mensaje' => 'Producto registrado correctamente',
                'data'    => $resultado['data']
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'estado'  => 'error',
                'mensaje' => 'Datos inválidos',
                'errores' => $e->errors()
            ], 422);

        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'estado'  => 'error',
                'mensaje' => 'No se pudo registrar el producto'
            ], 500);
        }
    }

    public function ListaProducto(Request $request){
        try {
            $resultado = $this->service->ServListaProducto();

            return response()->json([
                'estado'  => 'success',
                'mensaje' => 'Lista de productos',
                'data'    => $resultado
            ], 200);

        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'estado'  => 'error',
                'mensaje' => 'No se pudo obtener la lista de productos'
            ], 500);
        }
    }

    public function EditaProducto(Request $request){
        try {
            $Objeto = $request->user();

            $datos = $request->validate([
                'idproductos'                   => 'required|integer',
                'codigo_barras'                 => 'nullable|string|max:50',
                'codigo_producto_proveedor'     => 'nullable|string|max:45',
                'nombre'                        => 'nullable|string|max:150',
                'descripcion_corta'             => 'nullable|string|max:255',
                'descripcion_larga'             => 'nullable|string|max:255',
                'marca'                         => 'nullable|string|max:45',
                'imagen_url'                    => 'nullable|string|max:255',
                'activo_catalogo'               => 'nullable|boolean',
                'categoria_idcategorias'        => 'nullable|integer',
                'presentacion_idpresentacion'   => 'nullable|integer',
                'proveedores_idproveedores'     => 'nullable|integer',
            ]);

            $resultado = $this->service->ServEditaProducto($Objeto->idusuario, $datos['idproductos'], $datos);

            return response()->json([
                'estado'  => 'success',
                'mensaje' => 'Se actualizó el producto correctamente',
                'data'    => $resultado
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'estado'  => 'error',
                'mensaje' => 'Datos inválidos',
                'errores' => $e->errors()
            ], 422);

        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'estado'  => 'error',
                'mensaje' => 'No se pudo actualizar el producto'
            ], 500);
        }
    }

    public function EliminaProducto(Request $request){
        try {
            $datos = $request->validate([
                'idproductos' => 'required|integer'
            ]);

            $resultado = $this->service->ServEliminaProducto($datos['idproductos']);

            return response()->json([
                'estado'  => 'success',
                'mensaje' => 'Producto eliminado correctamente',
                'data'    => $resultado
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'estado'  => 'error',
                'mensaje' => 'Datos inválidos',
                'errores' => $e->errors()
            ], 422);

        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'estado'  => 'error',
                'mensaje' => 'No se pudo eliminar el producto'
            ], 500);
        }
    }

    public function ActivarProducto(Request $request){
        try {
            $datos = $request->validate([
                'codigo_producto_proveedor' => 'required|string|max:45'
            ]);

            $resultado = $this->service->ServActivarProducto($datos['codigo_producto_proveedor']);

            return response()->json([
                'estado'  => 'success',
                'mensaje' => 'Producto activado correctamente',
                'data'    => $resultado
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'estado'  => 'error',
                'mensaje' => 'Datos inválidos',
                'errores' => $e->errors()
            ], 422);

        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'estado'  => 'error',
                'mensaje' => 'No se pudo activar el producto'
            ], 500);
        }
    }
    
    //BUSCADOR PO TEXTO
    
    public function busquedatextoProducto(Request $request){
        try {
            $datos = $request->validate([
                'texto' => 'required|string|max:150'
            ]);

            $resultado = $this->service->ServBusquedaTextoProducto($datos['texto']);

            return response()->json([
                'estado'  => 'success',
                'mensaje' => 'Resultados de búsqueda',
                'data'    => $resultado
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'estado'  => 'error',
                'mensaje' => 'Datos inválidos',
                'errores' => $e->errors()
            ], 422);

        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'estado'  => 'error',
                'mensaje' => 'No se pudo realizar la búsqueda'
            ], 500);
        }
    }

    public function hola (Request $request){  

    }
}