<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\ProveedorContactoController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CategoriaController;
use App\http\Controllers\PresentacionController;
use App\Http\Controllers\SucursalController;
use App\Http\Controllers\InventarioSucursalController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\CompraDetalleController;
use App\Http\Controllers\PrecioVentaProductoController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\DetalleVentaController;
use App\Http\Controllers\MovimientoInventarioController;
use App\Http\Controllers\PrecioCompraProductoController;




Route::post('/registroUsuario', [AuthController::class,'registroUsuario']);
Route::post('/login', [AuthController::class,'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/login', function () {
    return response()->json(['message' => 'No autenticado'], 401);
})->name('login');

Route::middleware('auth:sanctum')->group(function () {

    // usuarios
    Route::get('/maqtown',[UsuarioController::class, 'listaUsuario'])->middleware('permiso:ver_usuario');
    Route::post('/usuario',[UsuarioController::class, 'editaUsuario'])->middleware('permiso:editar_usuario');
    Route::post('/crea_usuario',[UsuarioController::class, 'creaUsuario'])->middleware('permiso:crear_usuario');
    Route::post('/elimina_usuario',[UsuarioController::class, 'eliminaUsuario'])->middleware('permiso:eliminar_usuario');

    // proveedores
    Route::post('/reg_proveedor',[ProveedorController::class, 'registraProveedor'])->middleware('permiso:crear_proveedor');
    Route::get('/lista_proveedor',[ProveedorController::class, 'listaProveedor'])->middleware('permiso:ver_proveedor');
    Route::post('/edita_proveedor',[ProveedorController::class, 'editaProveedor'])->middleware('permiso:editar_proveedor');
    Route::post('/elimina_proveedor',[ProveedorController::class, 'eliminaProveedor'])->middleware('permiso:eliminar_proveedor');

    // clientes
    Route::post('/reg_cliente',[ClienteController::class, 'registraCliente'])->middleware('permiso:crear_cliente');
    Route::get('/lista_cliente',[ClienteController::class, 'listaCLiente'])->middleware('permiso:ver_cliente');
    Route::post('/edita_cliente',[ClienteController::class, 'editaCliente'])->middleware('permiso:editar_cliente');
    Route::post('/elimina_cliente',[ClienteController::class, 'eliminacliente'])->middleware('permiso:eliminar_cliente');

    // productos
    Route::post('/reg_producto', [ProductoController::class, 'RegistraProducto'])->middleware('permiso:crear_producto');
    Route::get('/lista_producto', [ProductoController::class, 'ListaProducto'])->middleware('permiso:ver_producto');
    Route::post('/edita_producto', [ProductoController::class, 'EditaProducto'])->middleware('permiso:editar_producto');
    Route::post('/elimina_producto', [ProductoController::class, 'EliminaProducto'])->middleware('permiso:eliminar_producto');
    Route::post('/activar_producto', [ProductoController::class, 'ActivarProducto'])->middleware('permiso:activar_producto');
    Route::get('/busqueda_texto_producto', [ProductoController::class, 'busquedatextoProducto'])->middleware('permiso:ver_producto');

    // categorias
    Route::post('/reg_categoria',[CategoriaController::class, 'RegistraCategoria'])->middleware('permiso:crear_categoria');
    Route::get('/lista_categoria',[CategoriaController::class, 'ListaCategoria'])->middleware('permiso:ver_categoria');
    Route::post('/edita_categoria',[CategoriaController::class, 'EditaCategoria'])->middleware('permiso:editar_categoria');
    Route::post('/elimina_categoria',[CategoriaController::class, 'EliminaCategoria'])->middleware('permiso:eliminar_categoria');

    // presentacion
    Route::post('/reg_presentacion',[PresentacionController::class, 'RegistraPresentacion'])->middleware('permiso:crear_presentacion');
    Route::get('/lista_presentacion',[PresentacionController::class, 'ListaPresentacion'])->middleware('permiso:ver_presentacion');
    Route::post('/edita_presentacion',[PresentacionController::class, 'EditaPresentacion'])->middleware('permiso:editar_presentacion');
    Route::post('/elimina_presentacion',[PresentacionController::class, 'EliminaPresentacion'])->middleware('permiso:eliminar_presentacion');

    // sucursales
    Route::post('/reg_sucursal', [SucursalController::class, 'RegistraSucursal'])->middleware('permiso:crear_sucursal');
    Route::get('/lista_sucursal', [SucursalController::class, 'ListaSucursal'])->middleware('permiso:ver_sucursal');
    Route::post('/edita_sucursal', [SucursalController::class, 'EditaSucursal'])->middleware('permiso:editar_sucursal');
    Route::post('/elimina_sucursal', [SucursalController::class, 'EliminaSucursal'])->middleware('permiso:eliminar_sucursal');

    // inventario_sucursales
    Route::get('/lista_inventario_sucursal', [InventarioSucursalController::class, 'ListaInventarioSucursal'])->middleware('permiso:ver_inventario_sucursal');
    Route::post('/edita_inventario_sucursal', [InventarioSucursalController::class, 'EditaInventarioSucursal'])->middleware('permiso:editar_inventario_sucursal');

    // compra
    Route::post('/reg_compra', [CompraController::class, 'RegistraCompra'])->middleware('permiso:crear_compra');
    Route::get('/lista_compra', [CompraController::class, 'ListaCompra'])->middleware('permiso:ver_compra');
    Route::post('/edita_compra', [CompraController::class, 'EditaCompra'])->middleware('permiso:editar_compra');
    Route::post('/buscar_id_compra', [CompraController::class, 'buscarIdCompra'])->middleware('permiso:ver_compra');
    Route::post('/elimina_compra', [CompraController::class, 'EliminaCompra'])->middleware('permiso:eliminar_compra');

    // compra_detalle
    Route::post('/agrega_compra_detalle', [CompraDetalleController::class, 'AgregaCompraDetalle'])->middleware('permiso:agregar_compra_detalle');
    Route::post('/edita_compra_detalle', [CompraDetalleController::class, 'EditaCompraDetalle'])->middleware('permiso:editar_compra_detalle');
    Route::post('/elimina_compra_detalle', [CompraDetalleController::class, 'EliminaCompraDetalle'])->middleware('permiso:eliminar_compra_detalle');

    // precios de venta
    Route::post('/reg_precio_venta_producto',[PrecioVentaProductoController::class, 'RegistraPrecioVentaProducto'])->middleware('permiso:crear_precio_venta_producto');
    Route::get('/lista_precio_venta_producto',[PrecioVentaProductoController::class, 'ListaPrecioVentaProducto'])->middleware('permiso:ver_precio_venta_producto');
    Route::post('/edita_precio_venta_producto',[PrecioVentaProductoController::class, 'EditaPrecioVentaProducto'])->middleware('permiso:editar_precio_venta_producto');
    Route::post('/elimina_precio_venta_producto',[PrecioVentaProductoController::class, 'EliminaPrecioVentaProducto'])->middleware('permiso:eliminar_precio_venta_producto');

    // ventas
    Route::post('/reg_venta', [VentaController::class, 'RegistraVenta'])->middleware('permiso:crear_venta');
    Route::get('/lista_venta', [VentaController::class, 'ListaVenta'])->middleware('permiso:ver_venta');
    Route::get('/lista_venta_anulada', [VentaController::class, 'ListaVentaAnulada'])->middleware('permiso:ver_venta_anulada');
    Route::post('/buscar_id_venta', [VentaController::class, 'buscarIdVenta'])->middleware('permiso:ver_venta');
    Route::post('/edita_venta', [VentaController::class, 'EditaVenta'])->middleware('permiso:editar_venta');
    Route::post('/anula_venta', [VentaController::class, 'AnulaVenta'])->middleware('permiso:anula_venta');

    // detalle venta
    Route::post('/agrega_detalle_venta', [DetalleVentaController::class, 'AgregaDetalleVenta'])->middleware('permiso:agregar_detalle_venta');
    Route::post('/edita_detalle_venta', [DetalleVentaController::class, 'EditaDetalleVenta'])->middleware('permiso:editar_detalle_venta');
    Route::post('/elimina_detalle_venta', [DetalleVentaController::class, 'EliminaDetalleVenta'])->middleware('permiso:eliminar_detalle_venta');
    
    // movimiento_inventario
    Route::get('/lista_movimiento_inventario', [MovimientoInventarioController::class, 'ListaMovimientoInventario'])->middleware('permiso:ver_movimiento_inventario');

    // precio_compra_producto
    Route::get('/lista_precio_compra_producto', [PrecioCompraProductoController::class, 'ListaPrecioCompraProducto'])->middleware('permiso:ver_precio_compra_producto');

    // proveedor_contacto
    Route::post('/reg_proveedor_contacto', [ProveedorContactoController::class, 'RegistraProveedorContacto'])->middleware('permiso:crear_proveedor_contacto');
    Route::get('/lista_proveedor_contacto', [ProveedorContactoController::class, 'ListaProveedorContacto'])->middleware('permiso:ver_proveedor_contacto');
    Route::post('/edita_proveedor_contacto', [ProveedorContactoController::class, 'EditaProveedorContacto'])->middleware('permiso:editar_proveedor_contacto');
    Route::post('/elimina_proveedor_contacto', [ProveedorContactoController::class, 'EliminaProveedorContacto'])->middleware('permiso:eliminar_proveedor_contacto');

    //asta qui deveria estar

});