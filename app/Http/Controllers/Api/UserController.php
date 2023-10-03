<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    // user login
    public function login(Request $request){

        $user_login = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',

        ]);
        //si les credencials estan malament

        if ( !Auth::attempt($user_login)){
            return response(['message' => 'Invalid login credentials']);
        }




    }


}
