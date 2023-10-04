<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\PersonalAccessTokenResult;
use App\Models\User;



class UserController extends Controller
{

    // user login
    public function login(Request $request){

        $user_login = $request->validate ([
            'email' => 'required|string',
            'password' => 'required|string',

        ]);
        //revisa las credenciales. Si son correctas:

        if(Auth::attempt($user_login)) {

            $user = Auth::user();
            $token = $user->createToken('user_token')->accessToken;

            return response()->json ([
                'user' => $user,
                'message' => 'Successfully logged in',
                'access_token' => $token,

            ], 200);
        }
        
        //si no son correctas
        else {
            return response()->json
            (['message' => 'Invalid login credentials'
            ], 401);
        }

    }


}
