<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Roles extends Model
{
    protected $table = 'roles';
    protected $primaryKey = 'idRoles';
    public $timestamps = false; // porque usas fecha_registro/fecha_mod, no created_at/updated_at

    protected $fillable = ['nombre'];

    // Un rol tiene muchos usuarios
    public function usuarios()
    {
        return $this->hasMany(AuthModel::class, 'Roles_idRoles', 'idRoles');
    }
    
    public function permisos()
    {
        return $this->belongsToMany(
            Permiso::class,      // modelo del otro lado
            'rol_permiso',       // tabla pivote
            'idRoles',           // FK de este modelo en la pivote
            'idPermisos',        // FK del otro modelo en la pivote
            'idRoles',           // PK local
            'idPermisos'         // PK del otro modelo
        )->withPivot('activo');
    }
    public function esSuperiorA(Roles $otroRol): bool
    {
        return $this->idRoles < $otroRol->idRoles;
    }
}