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
}
