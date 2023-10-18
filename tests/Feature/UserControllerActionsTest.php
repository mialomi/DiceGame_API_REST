<?php

namespace Tests\Feature;

Use App\Models\Game;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\DatabaseTransactions;
//use Illuminate\Foundation\Testing\RefreshDatabase;

use Tests\TestCase;

class UserControllerActionsTest extends TestCase
{
    use DatabaseTransactions;
    
    //List players
    public function test_admin_can_list_players(): void
    {
        $user = User::factory()->create([
            'nickname' => 'AdminNickname',
            'email' => 'admin@example.com',
            'password' => '123456789',
            'role_id' => Role::where('name', 'admin')->first()->id,
        ]);

        $player1 = User::factory()->create([
            'nickname' => 'Player1',
            'email' => 'player1@example.com',
            'password' => '123456789',
            'role_id' => Role::where('name', 'player')->first(),
        ]);
    
        $player2 = User::factory()->create([
            'nickname' => 'Player2',
            'email' => 'player@example.com',
            'password' => '123456789',
            'role_id' => Role::where('name', 'player')->first(),
        ]);

        $game1 = Game::factory()->create([
            'dice1' => '6',
            'dice2' => '5',
            'result' => '11',
            'user_id' =>$player1->id
        ]);
        $game2 = Game::factory()->create([
            'dice1' => '3',
            'dice2' => '4',
            'result' => '7',
            'user_id' =>$player2->id
        ]);
    
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $user->createToken('admin_token', ['admin'])->accessToken,
        ])->getJson('/api/players');

        //dump($response->content());
    
        $response->assertStatus(200)
        ->assertJsonIsArray();
    }

    public function test_player_cannot_list_players(): void
    {
        $user = User::factory()->create([
            'nickname' => 'PlayerNickname',
            'email' => 'player@example.com',
            'password' => '123456789',
            'role_id' => Role::where('name', 'player')->first()->id,
        ]);
    
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $user->createToken('player_token', ['player'])->accessToken,
        ])->getJson('/api/players');

        //dump($response->content());
    
        $response->assertForbidden();
        
    }

    //ranking players
    public function test_admin_can_list_ranking(): void
    {
        $user = User::factory()->create([
            'nickname' => 'AdminNickname',
            'email' => 'admin@example.com',
            'password' => '123456789',
            'role_id' => Role::where('name', 'admin')->first()->id,
        ]);
    
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $user->createToken('admin_token', ['admin'])->accessToken,
        ])->getJson('/api/players/ranking');

        //dump($response->content());
    
        $response->assertStatus(200);

        $response->assertJsonPath('0', 'The average of all players is 16.67 %');
        $response->assertJsonPath('1.0.Position', 1);
        $response->assertJsonPath('1.0.nickname', 'Elisia');
        $response->assertJsonPath('1.0.rate', 'The success rate is 20.00 % ');
        $response->assertJsonPath('1.1.Position', 2);
        $response->assertJsonPath('1.1.nickname', 'Mialomi');
        $response->assertJsonPath('1.1.rate', 'The success rate is 14.29 % ');
        }

        public function test_player_cannot_ranking_players(): void
        {
            $user = User::factory()->create([
                'nickname' => 'playerNickname',
                'email' => 'player@example.com',
                'password' => '123456789',
                'role_id' => Role::where('name', 'player')->first()->id,
            ]);
        
            $response = $this->withHeaders([
                'Authorization' => 'Bearer ' . $user->createToken('player_token', ['player'])->accessToken,
            ])->getJson('/api/players/ranking');
    
            //dump($response->content());
        
            $response->assertForbidden();
            
        }
    
        //ranking winner
        public function test_admin_can_see_winner(): void
        {
            $user = User::factory()->create([
                'nickname' => 'AdminNickname',
                'email' => 'admin@example.com',
                'password' => '123456789',
                'role_id' => Role::where('name', 'admin')->first()->id,
            ]);
        
            $response = $this->withHeaders([
                'Authorization' => 'Bearer ' . $user->createToken('admin_token', ['admin'])->accessToken,
            ])->getJson('/api/players/ranking/winner');
    
            //dump($response->content());
        
            $response->assertStatus(200)
                    ->assertJsonIsArray();
            
        }

        public function test_admin_cannot_see_winner_with_role_player_and_token_admin(): void
        {
            $user = User::factory()->create([
                'nickname' => 'AdminNickname',
                'email' => 'admin@example.com',
                'password' => '123456789',
                'role_id' => Role::where('name', 'player')->first()->id,
            ]);
        
            $response = $this->withHeaders([
                'Authorization' => 'Bearer ' . $user->createToken('admin_token', ['admin'])->accessToken,
            ])->getJson('/api/players/ranking/winner');
    
            //dump($response->content());
        
            $response->assertStatus(403);
                    
        }
    
    //ranking looser
    public function test_admin_can_see_one_loser(): void
        {
            $user = User::factory()->create([
                'nickname' => 'AdminNickname',
                'email' => 'admin@example.com',
                'password' => '123456789',
                'role_id' => Role::where('name', 'admin')->first()->id,
            ]);
        
            $response = $this->withHeaders([
                'Authorization' => 'Bearer ' . $user->createToken('admin_token', ['admin'])->accessToken,
            ])->getJson('/api/players/ranking/loser');
    
            //dump($response->content());
        
            $response->assertStatus(200)
                    ->assertJsonIsArray()
                    ->assertJsonCount('1');
            
        }
        public function test_player_cannot_see_loser_list(): void
        {
            $user = User::factory()->create([
                'nickname' => 'PlayerNickname',
                'email' => 'player@example.com',
                'password' => '123456789',
                'role_id' => Role::where('name', 'player')->first()->id,
            ]);
        
            $response = $this->withHeaders([
                'Authorization' => 'Bearer ' . $user->createToken('player_token', ['player'])->accessToken,
            ])->getJson('/api/players/ranking/loser');
    
            //dump($response->content());
        
            $response->assertStatus(403);
                    
        }
    

}
