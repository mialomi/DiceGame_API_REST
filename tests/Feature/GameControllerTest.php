<?php

namespace Tests\Feature;

Use App\Models\Game;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\DatabaseTransactions;
//use Illuminate\Foundation\Testing\RefreshDatabase;

use Tests\TestCase;

class GameControllerTest extends TestCase
{
    use DatabaseTransactions;

    //Dice Roll
    public function test_games_player_can_roll_dice(): void
    {
        $user = User::factory()->create([
            'nickname' => 'playerNickname',
            'email' => 'player@example.com',
            'password' => '123456789',
            'role_id' => Role::where('name', 'player')->first()->id,
        ]);
    
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $user->createToken('player_token', ['player'])->accessToken,
        ])->postJson('/api/players/'.$user->id.'/games/');

        //dump($response->content());
    
        $response->assertStatus(200)
        ->assertJsonStructure([
            'message',
            'dice1',
            'dice2',
            'result',
            'rate'
        ]);

    }
    public function test_games_player1_cannot_play_in_roll_dice_of_player2(): void
    {
        $user1 = User::factory()->create([
            'nickname' => 'playerNickname',
            'email' => 'player@example.com',
            'password' => '123456789',
            'role_id' => Role::where('name', 'player')->first()->id,
        ]);
        $user2 = User::factory()->create([
            'nickname' => 'player2Nickname',
            'email' => 'player2@example.com',
            'password' => '123456789',
            'role_id' => Role::where('name', 'player')->first()->id,
        ]);
    //'.$user->id.'/games/'
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $user1->createToken('player_token', ['player'])->accessToken,
        ])->postJson('/api/players/'.$user2->id.'/games/');

        //dump($response->content());
    
        $response->assertStatus(403)
                ->assertJson(['error' => 'Hey, you are not allowed to play here! :(']);
    }

    //show players games list
    public function test_games_player_can_see_its_rolls(): void
    {
        $player = User::factory()->create([
            'nickname' => 'playerNickname',
            'email' => 'player@example.com',
            'password' => '123456789',
            'role_id' => Role::where('name', 'player')->first()->id,
        ]);

        $game1 = Game::create([
            'dice1' => '5',
            'dice2' => '5',
            'result' => '11',
            'user_id' =>$player->id
        ]);
    
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $player->createToken('player_token', ['player'])->accessToken,
        ])->getJson('/api/players/'.$player->id.'/games');

        //dump($response->content());
    
        $response->assertStatus(200);
        $this->assertDatabaseHas(
            'games',
            [
                'dice1' =>$game1->dice1,
                'dice2' =>$game1->dice2,
                'result' =>$game1->result,
                'user_id' =>$player->id,
            ]);
    }

    public function test_games_admin_cannot_see_players_rolls_list(): void
    {
        $user1 = User::factory()->create([
            'nickname' => 'adminNickname',
            'email' => 'admin@example.com',
            'password' => '123456789',
            'role_id' => Role::where('name', 'admin')->first()->id,
        ]);
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $user1->createToken('admin_token', ['admin'])->accessToken,
        ])->getJson('/api/players/'.$user1->id.'/games');

        //dump($response->content());
    
        $response->assertStatus(403);
    }

    //delete rolls
    public function test_games_delete_rolls_works(): void
    {
        $user1 = User::factory()->create([
            'nickname' => 'playerNickname',
            'email' => 'player@example.com',
            'password' => '123456789',
            'role_id' => Role::where('name', 'player')->first()->id,
        ]);
        $game1 = Game::create([
            'dice1' => '5',
            'dice2' => '5',
            'result' => '12',
            'user_id' =>$user1->id
        ]);
    
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $user1->createToken('player_token', ['player'])->accessToken,
        ])->deleteJson('/api/players/'.$user1->id.'/games');

        //dump($response->content());
    
        $response->assertStatus(200);
        $this->assertDatabaseMissing(
            'games',
            [
                'dice1' =>$game1->dice1,
                'dice2' =>$game1->dice2,
                'result' =>$game1->result,
                'user_id' =>$user1->id,
            ]);
            
        $response->assertJson(['message' => 'Your records were successfully deleted.']);
        
    }

    public function test_games_admin_cannot_delete_players_rolls(): void
    {
        $admin = User::factory()->create([
            'nickname' => 'adminNickname',
            'email' => 'admin@example.com',
            'password' => '123456789',
            'role_id' => Role::where('name', 'admin')->first()->id,
        ]);
        $player = User::factory()->create([
            'nickname' => 'player2Nickname',
            'email' => 'player2@example.com',
            'password' => '123456789',
            'role_id' => Role::where('name', 'player')->first()->id,
        ]);
    
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $admin->createToken('player_token', ['admin'])->accessToken,
        ])->deleteJson('/api/players/'.$player->id.'/games');

        //dump($response->content());
    
        $response->assertStatus(403)
                ->assertJson(['error' => 'Hey, you are not allowed to delete this! :(']);
    }



}
