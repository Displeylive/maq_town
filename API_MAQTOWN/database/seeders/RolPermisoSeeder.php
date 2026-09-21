<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Roles;
use App\Models\Permiso;

class RolPermisoSeeder extends Seeder
{
    public function run(): void
    {
        $todos = Permiso::pluck('nombre')->toArray();

        $matriz = [
            'superadmin' => $todos, // absolutamente todo

            // admin: todo excepto gestión de usuarios (eso es solo de superadmin)
            'admin' => array_values(array_filter($todos, fn($p) => !str_ends_with($p, '_usuario'))),

            'supervisor' => [
                'ver_proveedor','crear_proveedor','editar_proveedor',
                'ver_cliente','crear_cliente','editar_cliente',
                'ver_producto','crear_producto','editar_producto','activar_producto',
                'ver_categoria','crear_categoria','editar_categoria',
                'ver_presentacion','crear_presentacion','editar_presentacion',
                'ver_sucursal','editar_sucursal',
                'ver_inventario_sucursal','editar_inventario_sucursal',
                'ver_compra','crear_compra','editar_compra',
                'agregar_compra_detalle','editar_compra_detalle',
                'ver_precio_venta_producto','crear_precio_venta_producto','editar_precio_venta_producto',
                'ver_venta','crear_venta','editar_venta','anula_venta','ver_venta_anulada',
                'agregar_detalle_venta','editar_detalle_venta',

                'ver_movimiento_inventario',
                'ver_precio_compra_producto',
                'crear_proveedor_contacto','ver_proveedor_contacto','editar_proveedor_contacto',

            ],

            'operador' => [
                'ver_producto','crear_producto','editar_producto','activar_producto',
                'ver_categoria','crear_categoria','editar_categoria',
                'ver_presentacion','crear_presentacion','editar_presentacion',
                'ver_inventario_sucursal','editar_inventario_sucursal',
                'ver_compra','crear_compra','editar_compra',
                'agregar_compra_detalle','editar_compra_detalle',

                'ver_movimiento_inventario',
            ],

            'usuario' => [
                'ver_producto','ver_categoria','ver_presentacion',
                'ver_cliente','ver_proveedor','ver_sucursal',
                'ver_inventario_sucursal','ver_compra',
                'ver_precio_venta_producto','ver_venta',

                 'ver_movimiento_inventario',
            ],

            'invitado' => [
                'ver_producto','ver_categoria',
            ],

            'vendedor' => [
                'ver_producto','ver_precio_venta_producto',
                'crear_cliente','ver_cliente','editar_cliente',
                'crear_venta','ver_venta',
                'agregar_detalle_venta','editar_detalle_venta',
            ],

            'Delivery' => [
                'ver_venta',
            ],
        ];

        foreach ($matriz as $nombreRol => $permisosDelRol) {
            $rol = Roles::where('nombre', $nombreRol)->first();

            if (!$rol) {
                $this->command->warn("Rol '$nombreRol' no encontrado en la BD, se omite.");
                continue;
            }

            $idsPermisos = Permiso::whereIn('nombre', $permisosDelRol)->pluck('idPermisos');

            foreach ($idsPermisos as $idPermiso) {
                DB::table('rol_permiso')->updateOrInsert(
                    ['idRoles' => $rol->idRoles, 'idPermisos' => $idPermiso],
                    ['activo' => true, 'fecha_registro' => now()]
                );
            }
        }
    }
}