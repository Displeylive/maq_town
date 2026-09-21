<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\AuthModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Services\UsuarioService;


class AuthController extends Controller
{

        public function __construct(
        protected UsuarioService $service
        ) {}
    
        /*  asi se debe llamar a los metodos de UsuarioService
            $usuario = $this->service->obtenerUsuario($userId);
        //recupera el id del usuario activo
       // $Objeto=$request->user();*/


    public function registroUsuario(Request $request){
      // valida si los valores de registro son correctos
      $request->validate([
         'nombre' => 'required|string',
         'nombreUsuario' => 'required|string',
         'telefono'=> 'required',
         'password' => 'required|string',
        ]);


      // encripta la contraseña nueva       
        //$request->password=Hash::make($request->password);

        $request->merge([
            'password' => Hash::make($request->input('password'))
        ]);

     // manda los datos a la base para registro 
     
     $resultado=$this->service->registroUsuario($request);
     // verificando y  repondiendo
          if(!$resultado){
              return response()->json([
              'mensaje' => 'se registro correctamente',
              'data'=>$resultado
            ],201);
         }
        else{
                return response()->json([
                'mensaje' => 'no se pudo registrar'
            ], 400);
        } 

    }

// funcion para logueo e inicio de session  
    public function login(Request $request)
    {
        $request->validate([
            'nombreUsuario' => 'required|string',
            'password' => 'required'
        ]);
        $usuario = AuthModel::where('nombre_usuario', $request->nombreUsuario)->first();

        if ($usuario && Hash::check($request->password, $usuario->password)) {
            // Como el modelo usa HasApiTokens, el método funcionará
            $token = $usuario->createToken('token-de-api')->plainTextToken;
            return response()->json([
                'user' => $usuario,
                'token' => $token
                ], 200);
        }
        else{
            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }
    }

      public function logout(Request $request)
    {   
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Sesión cerrada' ], 200);
    }

   
}
