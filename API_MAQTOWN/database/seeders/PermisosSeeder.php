<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permiso;

class PermisosSeeder extends Seeder
{
    public function run(): void
    {
        $permisos = [
            // usuario
            ['nombre' => 'ver_usuario',      'modulo' => 'usuario', 'descripcion' => 'Ver usuarios'],
            ['nombre' => 'crear_usuario',    'modulo' => 'usuario', 'descripcion' => 'Crear usuarios'],
            ['nombre' => 'editar_usuario',   'modulo' => 'usuario', 'descripcion' => 'Editar usuarios'],
            ['nombre' => 'eliminar_usuario', 'modulo' => 'usuario', 'descripcion' => 'Eliminar usuarios'],

            // proveedor
            ['nombre' => 'crear_proveedor',    'modulo' => 'proveedor', 'descripcion' => 'Crear proveedores'],
            ['nombre' => 'ver_proveedor',      'modulo' => 'proveedor', 'descripcion' => 'Ver proveedores'],
            ['nombre' => 'editar_proveedor',   'modulo' => 'proveedor', 'descripcion' => 'Editar proveedores'],
            ['nombre' => 'eliminar_proveedor', 'modulo' => 'proveedor', 'descripcion' => 'Eliminar proveedores'],

            // cliente
            ['nombre' => 'crear_cliente',    'modulo' => 'cliente', 'descripcion' => 'Crear clientes'],
            ['nombre' => 'ver_cliente',      'modulo' => 'cliente', 'descripcion' => 'Ver clientes'],
            ['nombre' => 'editar_cliente',   'modulo' => 'cliente', 'descripcion' => 'Editar clientes'],
            ['nombre' => 'eliminar_cliente', 'modulo' => 'cliente', 'descripcion' => 'Eliminar clientes'],

            // producto
            ['nombre' => 'crear_producto',    'modulo' => 'producto', 'descripcion' => 'Crear productos'],
            ['nombre' => 'ver_producto',      'modulo' => 'producto', 'descripcion' => 'Ver productos (incluye búsqueda por texto)'],
            ['nombre' => 'editar_producto',   'modulo' => 'producto', 'descripcion' => 'Editar productos'],
            ['nombre' => 'eliminar_producto', 'modulo' => 'producto', 'descripcion' => 'Eliminar productos'],
            ['nombre' => 'activar_producto',  'modulo' => 'producto', 'descripcion' => 'Activar/reactivar productos'],

            // categoria
            ['nombre' => 'crear_categoria',    'modulo' => 'categoria', 'descripcion' => 'Crear categorías'],
            ['nombre' => 'ver_categoria',      'modulo' => 'categoria', 'descripcion' => 'Ver categorías'],
            ['nombre' => 'editar_categoria',   'modulo' => 'categoria', 'descripcion' => 'Editar categorías'],
            ['nombre' => 'eliminar_categoria', 'modulo' => 'categoria', 'descripcion' => 'Eliminar categorías'],

            // presentacion
            ['nombre' => 'crear_presentacion',    'modulo' => 'presentacion', 'descripcion' => 'Crear presentaciones'],
            ['nombre' => 'ver_presentacion',      'modulo' => 'presentacion', 'descripcion' => 'Ver presentaciones'],
            ['nombre' => 'editar_presentacion',   'modulo' => 'presentacion', 'descripcion' => 'Editar presentaciones'],
            ['nombre' => 'eliminar_presentacion', 'modulo' => 'presentacion', 'descripcion' => 'Eliminar presentaciones'],

            // sucursal
            ['nombre' => 'crear_sucursal',    'modulo' => 'sucursal', 'descripcion' => 'Crear sucursales'],
            ['nombre' => 'ver_sucursal',      'modulo' => 'sucursal', 'descripcion' => 'Ver sucursales'],
            ['nombre' => 'editar_sucursal',   'modulo' => 'sucursal', 'descripcion' => 'Editar sucursales'],
            ['nombre' => 'eliminar_sucursal', 'modulo' => 'sucursal', 'descripcion' => 'Eliminar sucursales'],

            // inventario_sucursal
            ['nombre' => 'ver_inventario_sucursal',    'modulo' => 'inventario_sucursal', 'descripcion' => 'Ver inventario por sucursal'],
            ['nombre' => 'editar_inventario_sucursal', 'modulo' => 'inventario_sucursal', 'descripcion' => 'Editar inventario por sucursal'],

            // compra
            ['nombre' => 'crear_compra',    'modulo' => 'compra', 'descripcion' => 'Registrar compras'],
            ['nombre' => 'ver_compra',      'modulo' => 'compra', 'descripcion' => 'Ver compras (incluye búsqueda por id)'],
            ['nombre' => 'editar_compra',   'modulo' => 'compra', 'descripcion' => 'Editar compras'],
            ['nombre' => 'eliminar_compra', 'modulo' => 'compra', 'descripcion' => 'Eliminar compras'],

            // compra_detalle
            ['nombre' => 'agregar_compra_detalle', 'modulo' => 'compra_detalle', 'descripcion' => 'Agregar detalle de compra'],
            ['nombre' => 'editar_compra_detalle',  'modulo' => 'compra_detalle', 'descripcion' => 'Editar detalle de compra'],
            ['nombre' => 'eliminar_compra_detalle','modulo' => 'compra_detalle', 'descripcion' => 'Eliminar detalle de compra'],

            // precio_venta_producto
            ['nombre' => 'crear_precio_venta_producto',    'modulo' => 'precio_venta_producto', 'descripcion' => 'Crear precio de venta'],
            ['nombre' => 'ver_precio_venta_producto',      'modulo' => 'precio_venta_producto', 'descripcion' => 'Ver precios de venta'],
            ['nombre' => 'editar_precio_venta_producto',   'modulo' => 'precio_venta_producto', 'descripcion' => 'Editar precio de venta'],
            ['nombre' => 'eliminar_precio_venta_producto', 'modulo' => 'precio_venta_producto', 'descripcion' => 'Eliminar precio de venta'],

            // venta
            ['nombre' => 'crear_venta',        'modulo' => 'venta', 'descripcion' => 'Registrar ventas'],
            ['nombre' => 'ver_venta',          'modulo' => 'venta', 'descripcion' => 'Ver ventas (incluye búsqueda por id)'],
            ['nombre' => 'editar_venta',       'modulo' => 'venta', 'descripcion' => 'Editar ventas'],
            ['nombre' => 'anula_venta',        'modulo' => 'venta', 'descripcion' => 'Anular ventas'],
            ['nombre' => 'ver_venta_anulada',  'modulo' => 'venta', 'descripcion' => 'Ver listado de ventas anuladas'],

            // detalle_venta
            ['nombre' => 'agregar_detalle_venta', 'modulo' => 'detalle_venta', 'descripcion' => 'Agregar detalle de venta'],
            ['nombre' => 'editar_detalle_venta',  'modulo' => 'detalle_venta', 'descripcion' => 'Editar detalle de venta'],
            ['nombre' => 'eliminar_detalle_venta','modulo' => 'detalle_venta', 'descripcion' => 'Eliminar detalle de venta'],

            // movimiento_inventario
            ['nombre' => 'ver_movimiento_inventario', 'modulo' => 'movimiento_inventario', 'descripcion' => 'Ver movimientos de inventario'],

            // precio_compra_producto
            ['nombre' => 'ver_precio_compra_producto', 'modulo' => 'precio_compra_producto', 'descripcion' => 'Ver precios de compra de productos'],

            // proveedor_contacto
            ['nombre' => 'crear_proveedor_contacto',   'modulo' => 'proveedor_contacto', 'descripcion' => 'Crear contacto de proveedor'],
            ['nombre' => 'ver_proveedor_contacto',     'modulo' => 'proveedor_contacto', 'descripcion' => 'Ver contactos de proveedor'],
            ['nombre' => 'editar_proveedor_contacto',  'modulo' => 'proveedor_contacto', 'descripcion' => 'Editar contacto de proveedor'],
            ['nombre' => 'eliminar_proveedor_contacto','modulo' => 'proveedor_contacto', 'descripcion' => 'Eliminar contacto de proveedor'],


        ];

        foreach ($permisos as $permiso) {
            Permiso::updateOrCreate(
                ['nombre' => $permiso['nombre']],
                $permiso
            );
        }
    }
}