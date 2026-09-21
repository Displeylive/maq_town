<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    protected $table = 'permisos';
    protected $primaryKey = 'idPermisos';
    public $timestamps = false;

    protected $fillable = ['nombre', 'descripcion', 'modulo', 'activo'];
}