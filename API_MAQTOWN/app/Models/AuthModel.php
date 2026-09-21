<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Clases\ClaseUsuario;
use Illuminate\Foundation\Auth\UsuarioModel as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Http\Request;
use App\Models\Roles;

class AuthModel extends Model  
{
    use HasApiTokens, Notifiable; 

    protected $table = 'usuario';
    protected $primaryKey = 'idusuario';
    public $timestamps = false; // 👈 agregar esta línea
    // protected $fillable = ['nombre','nombre_usuario', 'password', 'Roles_idRoles', 'Activo']; // 👈 agregué Roles_idRoles y Activo
    protected $fillable = ['nombre','nombre_usuario', 'password', 'telefono', 'Roles_idRoles', 'Activo','fecha_mod'];
    protected $hidden = ['password', 'fecha_mod']; // 👈 nuevo

    public function getAuthPassword() {
        return $this->password;
    }

    public function rol()
    {
        return $this->belongsTo(Roles::class, 'Roles_idRoles', 'idRoles');
    }
}