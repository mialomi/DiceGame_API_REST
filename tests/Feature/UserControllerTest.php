<?php

namespace Tests\Feature;

Use App\Models\Game;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserControllerTest extends TestCase
{ 
    use DatabaseTransactions;
    /**
     * A basic feature test example.
     */
    public function test_register_is_correct(): void
    {
        $response = $this->postJson('/api/register',[
            'nickname' => 'Testnickname',
            'email' => 'test@example.com',
            'password' => 'testing123',

        ]);    

        $response->assertStatus(201);
    }

    public function test_register_nickname_not_valid(): void
    {
        $response = $this->postJson('/api/register',[
            'nickname' => '/*-+=`',
            'email' => 'test@example.com',
            'password' => 'testing123'

        ]);    

        $response->assertJson(['message' => 'Invalid request']);
        
    }

    public function test_register_empty_nickname(): void
    {
        $response = $this->postJson('/api/register',[
            'nickname' => '',
            'email' => 'test@example.com',
            'password' => 'testing123'

        ]);    

        $response->assertStatus(201);

    }

    public function test_register_data_is_saved(): void
    {
        $response = $this->postJson('/api/register',[
            'nickname' => '',
            'email' => 'test@example.es',
            'password' => 'testing123'

        ]);    

        $response->assertStatus(201);
        $this->assertDatabaseMissing('users', [
            'email' => 'test@example.com' ]);
    }
    public function test_register_email_already_exist(): void
    {
        $response = $this->post('/api/register',[
            'nickname' => 'Mia',
            'email' => 'test@example.es',
            'password' => 'testing123'

        ]);    

        $response->assertStatus(422);
        $this->assertDatabaseMissing('users', [
            'email' => 'test@example.es' ]);
    }

    //LOGIN
    public function test_login_is_correct(): void
    {
        $response = $this->postJson('/api/register',[
            'nickname' => 'Testnickname',
            'email' => 'test@example.com',
            'password' => 'testing123',

        ]);    

        $response->assertStatus(201);
    }

    public function test_login_invalid_password(): void
    {
        $response = $this->postJson('/api/register',[
            'nickname' => 'Testnickname',
            'email' => 'test@example.com',
            'password' => '12345',

        ]);    

        $response->assertJson(['message' => 'Invalid request']);
    }

    public function test_login_as_player()
    {
        $player = User::create([
            'email' => 'player36@example.com',
            'password' => '123456789',
            'role_id' => Role::where('name', 'player')->first()->id,
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'player36@example.com',
            'password' => '123456789',
        ]);

        $response->assertStatus(200);
            $this->assertDatabaseHas('users', [
            'role_id' => '2' ]);
        
    }
    //update 
    public function test_user_can_update_nickname(): void
    
    {
        $user = User::factory()->create([
            'nickname' => 'OldNickname',
            'email' => 'old@example.com',
            'password' => '123456789',
            'role_id' => Role::where('name', 'player')->first()->id,
        ]);
    
        $newNickname = 'NewNickname';
    
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $user->createToken('player_token', ['player'])->accessToken,
        ])->putJson('/api/user/'.$user->id, [
            'nickname' => $newNickname,
        ]);

        //dump($response->content());
    
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Nickname successfully updated!',
            ]);
    
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'nickname' => $newNickname,
        ]);
        

    }

    public function test_user_admin_cannot_update_nickname(): void
    
    {
        $user = User::factory()->create([
            'nickname' => 'OldNickname',
            'email' => 'old@example.com',
            'password' => '123456789',
            'role_id' => Role::where('name', 'admin')->first()->id,
        ]);
    
        $newNickname = 'NewNickname';
    
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $user->createToken('admin_token', ['admin'])->accessToken,
        ])->putJson('/api/user/'.$user->id, [
            'nickname' => $newNickname,
        ]);

        //dump($response->content());
    
        $response->assertStatus(403)
            ->assertJson([
                'error' => 'Hey, you are not allowed to play! :(',
            ]);
        
    }

    public function test_user_player_can_update_with_empty_nickname_(): void
    
    {
        $user = User::factory()->create([
            'nickname' => 'OldNickname',
            'email' => 'old@example.com',
            'password' => '123456789',
            'role_id' => Role::where('name', 'player')->first()->id,
        ]);
    
        $newNickname = '';
    
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $user->createToken('player_token', ['player'])->accessToken,
        ])->putJson('/api/user/'.$user->id, [
            'nickname' => $newNickname,
        ]);

        //dump($response->content());
    
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Nickname successfully updated!',
            ]);
    
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'nickname' => 'Anonymous',
        ]);
        
    }

    //Logout
    public function test_logout_is_correct(): void
    {
        
            $user = User::factory()->create([
                'nickname' => 'OldNickname',
                'email' => 'old@example.com',
                'password' => '123456789',
                'role_id' => Role::where('name', 'player')->first(),
            ]);
            $response = $this->withHeaders([
                'Authorization' => 'Bearer ' . $user->createToken('player_token', ['player'])->accessToken,
            ])->getJson('/api/logout');

            dump($response->content());

            $response->assertStatus(200)
            ->assertJson([
                'message' => 'Successfully logged out. See you soon!'
            ]);
        
    }










}
