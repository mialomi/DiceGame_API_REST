<?php

namespace Tests\Feature;

Use App\Models\Game;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\DatabaseTransactions;
//use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GameControllerTest extends TestCase
{
    use DatabaseTransactions;

    //Dice Roll
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
