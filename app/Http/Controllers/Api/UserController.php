<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;



class UserController extends Controller
{

//register

    public function register(Request $request){

        //validamos los datos
      /*   $user_data = $request->validate ([
            'nickname' => 'nullable|string',
            'email' => 'required|string',
            'password' => 'required|min:8|confirmed',
        ]); */

        //creamos nuevo usuario 

        $user = User::create([

            'nickname' => $request->input('nickname'),
            'email' =>$request->input('email'),
            'password' =>bcrypt($request->input('password'))
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

       /* $rules = [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ];
        //establecemos mensajes de error concretos

        $errormsg = [
            'email.email' => 'Enter a valid email',
            'email.required' => 'Email field is required',
            'password.required' => 'Password field is required'

        ];
        // Empezamos la validación
        $validator = Validator::make($request-> all(), $rules, $errormsg);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();

        }*/

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



