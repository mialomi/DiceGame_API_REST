<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;



class UserController extends Controller
{

//register

    public function register(Request $request){


        $user_rules = [
            'nickname' => 'nullable|string|unique:users',
            'email' => 'required|string',
            'password' => 'required|string',
        ];

        $user_errormsg = [
            
            'email.email' => 'This email is already in use',
            'email.required' => 'Email field is required',
            'password.required' => 'Password field is required',
        ];
        //validamos datos introducidos
        $validator = Validator::make($request-> all(), $user_rules, $user_errormsg);

        if ($validator->fails()){
            return response()->json([
                'message' => 'Invalid request',
                'errors' => $validator->errors(),
    
            ], 422);
        }

       // Si el nickname queda vacío, asignamos el valor anonymous con el operador de fusión null

        $nickname = $request->input('nickname') ?? 'Anonymous';

        $user = User::create([

            'nickname' => $nickname,
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password'))
        ]);

        if ($user) {
            return response()->json([
                'message' => 'Successfully created',
            ], 201);

        }

    }

    // user login
    public function login(Request $request){

        // validamos datos, establecemos unas rules

        $rules = [
            'email' => 'required|string',
            'password' => 'required|string',
        ];
        //establecemos mensajes de error concretos

        $errormsg = [
            'email.email' => 'Enter a valid email',
            'email.required' => 'Email field is required',
            'password.required' => 'Password field is required'

        ];
        // valida según las reglas

        $validator = Validator::make($request-> all(), $rules, $errormsg);

        //si falla, devuelve un error 
        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();

        }
        //si sigue, pasamos a autenticar el usuario
        $user_login = [
            'email' => $request->input('email'),
            'password' => $request->input('password'),

        ];

        if(Auth::attempt($user_login)) {

            $user = Auth::user();
            /** @var \App\Models\User $user **/
            $token = $user->createToken('user_token')->accessToken;

            return response()->json ([
                'user' => $user,
                'message' => 'Successfully logged in',
                'access_token' => $token,

            ]);
         }
     
         //si no son correctas
        else {
            return response()->json
            (['message' => 'Invalid login credentials'
            ], 401);
        } 

    }

}



