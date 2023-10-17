<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Role;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;



class UserController extends Controller
{

    //register

    public function register(Request $request){


        $user_rules = [
            'nickname' => 'nullable|alpha_num:ascii|unique:users|max:20',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|min:8',
        ];

        $error_msg = [
            'nickname.alpha_num' => 'This is not a correct nickname. Use letters and numbers only.',
            'nickname.unique' => 'This nickname is already in use. Write a new nickname.',
            'nickname.max' => 'This nickname is too long. Only 20 characters allowed.',
            'email.email' => 'The email field must contain a valid email.',
            'email.required' => 'The email field is required.',
            'email.unique' => 'This email is already in use.',
            'password.min' => 'The password is not secure. It must contain a minimum of 8 characters.',
            'password.required' => 'Password field is required.',
            
        ];
        //validamos datos introducidos
        $validator = Validator::make($request-> all(), $user_rules, $error_msg);

        //Si algun dato input está mal, envía los mensajes de error

        if ($validator->fails()){
            return response()->json([
                'message' => 'Invalid request',
                'error' => $validator->errors(),
    
            ], 422);
        }


       // Si el nickname queda vacío, asignamos el valor anonymous con el operador de fusión null

        $nickname = $request->input('nickname') ?? 'Anonymous';

        // Si todo es correcto -> creamos un nuevo ususario y le asignamos el rol player
        $user = User::create([

            'nickname' => $nickname,
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'role_id' => Role::where('name', 'player')->first()->id,
        ]);

        if ($user) {
            return response()->json([
                'message' => 'Player successfully created :)',
            ], 201);

        }

    }

    // user login
    public function login(Request $request){

        // validamos datos, establecemos unas rules

        $rules = [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ];
        //establecemos mensajes de error concretos

        $error_msg = [
            'email.email' => 'Enter a valid email',
            'email.required' => 'Email field is required',
            'password.required' => 'Password field is required'

        ];
        // valida según las reglas

        $validator = Validator::make($request-> all(), $rules, $error_msg);

        //si falla, devuelve un error 
        if ($validator->fails()){
            return response()->json([
                'message' => 'Invalid request',
                'errors' => $validator->errors(),
    
            ], 401);
        }
        //si sigue, pasamos a autenticar el usuario
        $user_login = [
            'email' => $request->input('email'),
            'password' => $request->input('password'),

        ];

        if(Auth::attempt($user_login)) {

            $user = Auth::user();
            /** @var \App\Models\User $user **/
            $role = $user->role->name;

            if($role == 'player'){
                $token = $user->createToken('player_token', ['player'])->accessToken;
            }
            else if($role == 'admin'){
                $token = $user->createToken('admin_token', ['admin'])->accessToken;
            }

          //  $token = $user->createToken('user_token')->accessToken;

            return response()->json ([
                'user' => $user,
                'message' => 'Successfully logged in. Ready to play! ',
                'access_token' => $token,

            ], 200);
         }
     
         //si no son correctas
        else {
            return response()->json
            (['message' => 'Invalid login credentials :('
            ], 401);
        } 

    }
    //update nombre de usuario registrado
    public function update(Request $request, $id) {

        $user = User::find($id);

        if(!$user && $user->role->name != 'player' || $user->role->name != 'admin'){
            return response()->json([
                'message' => 'Sorry, you do not have permission to update this :(',
            ], 404);
        }

       $rules = [
            'nickname' => 'nullable|alpha_num:ascii|max:20|unique:users,nickname,'.$id,
            ];

       $error_msg = [
            'nickname.alpha_num' => 'This is not a correct nickname. Use letters and numbers only.',
            'nickname.max' => 'This nickname is too long. Only 20 characters allowed.',
            'nickname.unique' => 'This nickname is already taken. Please write a new nickname.',
       ];

       $validator = Validator::make($request->only('nickname'), $rules, $error_msg);

       if ($validator->fails()) {
            return response()->json([
            'message' => 'Invalid request',
            'error' => $validator->errors(),
            
            ], 422);
        }

        $new_nickname = $request->input('nickname');
        
        if ($new_nickname !== $user->nickname){
                
            $user->nickname = $new_nickname ?? 'Anonymous';
            
            $user->update();

            return response()->json([
                'message' => 'Nickname successfully updated!',
            ], 200);
    }

        return response()->json([
            'message' => 'Same nickname. No changes were made :(',
        ], 200);

    
    }

    public function logout() {
        
            /** @var \App\Models\User $user **/
          $user = Auth::user();
  
          $token = $user->token();
          $token->revoke();
  
          return response()->json([
            'message' => 'Successfully logged out. See you soon!'
        ], 200);
      }

      //listado con todas las tiradas con los resultados y su tasa de éxito
    

    public function list_players(Request $request){

        if($request->user()->role->name != 'admin' && !$request->user()->tokenCan('list_all_players')) {
            return response()->json([
                'error' => 'Hey, you are not allowed! :('
            ], 403);
        }

        $players = User::where('role_id', '2')->get();

        $players_list = [];

        foreach ($players as $player) {

        $player = [
            'nickname' => $player->nickname,
            'rate' => $player->calculate_rates($player->id),
        ];

            $players_list[] = $player;
        };  

        return $players_list;

       
    }

    public function ranking_players(Request $request){

        if($request->user()->role->name != 'admin' && !$request->user()->tokenCan('list_ranking')) {
            return response()->json([
                'error' => 'Hey, you are not allowed! :('
            ], 403);
        }
        
        //método para conseguir todos los jugadores

        $user = new User;
        $gamers_list = $user->get_players();
        
        //orden desc según el rate
        usort($gamers_list, function ($a, $b) {
            return $b['rate'] <=> $a['rate'];
        });

        $ranking_list = [];

        foreach($gamers_list as $key =>$player) {
            $list_position = [
                'Position' => $key +1,
                'nickname' => $player['nickname'],
                'rate' => $player['rate'],
            ];

            $ranking_list[] = $list_position;
        }

        $game = new Game;
        $global_rate = $game->calculate_global_rate();

        return response()->json([
            
            $global_rate,
            $ranking_list,
                    
            ]);
        
    }

    public function ranking_winner(Request $request){

        if($request->user()->role->name != 'admin' && !$request->user()->tokenCan('list_winner')) {
            return response()->json([
                'error' => 'Hey, you are not allowed! :('
            ], 403);
        }

        $user = new User;
        $winners_list = $user->get_players();

        usort($winners_list, function ($a, $b) {
            return $b['rate'] <=> $a['rate'];
        });

        return response()->json([

            $winners_list[0],
        ]);

    }

    public function ranking_loser(Request $request){

        if($request->user()->role->name != 'admin' && !$request->user()->tokenCan('list_loser')) {
            return response()->json([
                'error' => 'Hey, you are not allowed! :('
            ], 403);
        }

        $user = new User;
        $losers_list = $user->get_players();

        //return $players_list;

        usort($losers_list, function ($a, $b) {
            return $a['rate'] <=> $b['rate'];
        });

        return response()->json([

            $losers_list[0],
        ]);

    }


    }







    
        
    










