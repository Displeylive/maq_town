<?php

namespace App\Services;
use Illuminate\Http\Request;
use App\Models\AuthModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuarioService
{
    public function __construct() {}

    public function registroUsuario(Request $request){
        $rolUsuario = 7; // usuario (rol por defecto en autoregistro público)
        $resultados = DB::select('CALL SP_registroUsuario(?,?,?,?,?)',
            [$request->nombre, $request->nombreUsuario, $request->password, $rolUsuario, $request->telefono]);
        return $resultados;
    }

    // NUEVO: creación de usuario desde panel de admin, con rol elegido
    public function creaUsuarioAdmin(Request $request){
        $resultados = DB::select('CALL SP_registroUsuario(?,?,?,?,?)',
            [$request->nombre, $request->nombreUsuario, Hash::make($request->password), $request->rolId, $request->telefono]);
        return $resultados;
    }

    public function verificaUsuario(Request $request){
        $resultado = DB::select('CALL SP_verificaUsuario(?,?)', [$request->nombreUsuario, $request->password]);
        return $resultado;
    }

    public function ListaUsuario(){
        $usuarios = DB::select('CALL SP_listaUsuario()');
    
        // Quitamos el password antes de devolver los datos
        foreach ($usuarios as $usuario) {
            unset($usuario->password);
        }

        return $usuarios;

    }

    // COMPLETADO: ya no estaba implementado
    public function actualizaUsuario($id, $datos){
        $usuario = AuthModel::findOrFail($id);
        if (isset($datos['password'])) {
            $datos['password'] = Hash::make($datos['password']);
        }
        $usuario->update($datos);
        return $usuario;
    }

    // NUEVO: eliminación lógica
    public function eliminaUsuario($id){
        return AuthModel::where('idusuario', $id)->update(['Activo' => 0]);
    }
}