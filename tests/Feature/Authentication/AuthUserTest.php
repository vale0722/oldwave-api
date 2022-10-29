<?php

namespace Tests\Feature\Authentication;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_and_login(): void
    {
        $request = [
            'name' => 'alejo',
            'email' => 'alejo@gmail.com',
            'password' => 'testing123!',
        ];

        $response = $this->postJson(route('register.api'), $request);

        $response->assertOk();

        $requestLogin = [
            'email' => 'alejo@gmail.com',
            'password' => 'testing123!',
        ];

        $response2 = $this->postJson(route('login.api'), $requestLogin);

        $response2->assertOk();
    }
}
