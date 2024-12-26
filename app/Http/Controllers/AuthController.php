<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\User;
use \stdClass;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ]);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()
            ->json([
                'status' => 'Ok',
                'message' => 'Registro exitoso',
                'data' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]);
    }

    public function login(Request $request) {

        $credetentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        if(!Auth::attempt($credetentials)){
            return response()
                   ->json([
                    'status', 'error',
                    'message', 'Credenciales incorrectas. Por favor, verifica tu email y contraseña.'
                ], 401);
        }

        $user = User::where('email', $credetentials['email'])->first();
        
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'El usuario no existe',
            ], 404);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()
            ->json([
                'status' => 'Ok',
                'message' => 'Hola ' . $user->name .'. Acabas de iniciar sesion.',
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user,
            ]);
    }

    public function logout(){

        auth()->user()->tokens()->delete();

        return [
            "message" => "logout"
        ];
    }
}
