<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\UserController;
use App\Models\User;
use App\Models\Role;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class GameController extends Controller
{

    public function diceRoll(Request $request, $id) {

        $user = User::find($id);

            
        if (!$user && !$request->user()->id->hasRole('player') && !$request->user()->tokenCan('dice_roll')) {
          
            return response()->json([
                
                'error' => 'Hey, you are not allowed to play! :('
            
            ], 403);
        }
        //game_logic

        $dice1 = rand(1, 6);
        $dice2 = rand(1, 6);

        $result = $dice1 + $dice2;
        
        //new game

        $game = Game::create([

            'dice1' => $dice1,
            'dice2' => $dice2,
            'result' => $result,
            'user_id' => $request->user()->id

        ]);

        if ($result == 7) {
            return response()->json([
                'message' => 'You WIN!',
                'dice1' => $dice1,
                'dice2' => $dice2,
                'result' => $result,
                'rate' => $user->calculate_rates($id),

            ]);
        }

        else {
            return response()->json([
                'message' => 'You LOOSE!',
                'dice1' => $dice1,
                'dice2' => $dice2,
                'result' => $result,
                'rate' => $user->calculate_rates_($id),

            ]);

        }
    }    

        public function show_player_rolls(Request $request, $id){

            $user = User::find($id);
    
            if (!$user && !$request->user()->id->hasRole('player') && !$request->user()->tokenCan('list_rolls')) {
              
                return response()->json([
                    
                    'error' => 'Hey, you are not allowed! :('
                
                ], 403);
            }
    
            $rolls = Game::where('user_id', $id)->get();
            
            $player_rolls = [];
    
            foreach ($rolls as $roll) {
    
                $player_rolls [] = [
    
                    'message' => $roll->result == 7 ? 'You WIN!' : 'You LOOSE!',
                    'dice1' => $roll->dice1,
                    'dice2' => $roll->dice2,
                    'result' => $roll->result,
                    
                ];
            }
    
            if (empty($player_rolls)){
                return response()->json([
    
                    'error' => 'No records found. Start playing!',
                ]);
            }
            else {
    
                return response()->json([
    
                    $player_rolls,
                    'success rate' => $user->calculate_rates($id),
    
                ]);
            }
        }

    }
    











