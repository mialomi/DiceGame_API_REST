<?php

namespace Tests\Feature;
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
}
