<?php

namespace App\Services;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class PermisoService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
  
    }
        /* $rolUsuario=2;
        $resultados=DB::select('CALL SP_registroUsuario(?,?,?,?,?)',[ $request->nombre,$request->nombreUsuario,$request->password,$rolUsuario,$request->telefono]);
        return $resultados;*/

    public function permisos(Request $request){
       $resultados=DB:: select('CALL sp_permisos(?)'[$request->idusuario]);
        return $resultados;
    }

}
