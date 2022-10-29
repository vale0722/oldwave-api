<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Mockery;
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

        $this->assertDatabaseHas('users', [
            'name' => $request['name'],
            'email' => $request['email'],
        ]);
    }

    public function test_user_cannot_register_user_with_data_invalid(): void
    {
        $request = [
            'name' => 'alejo',
            'email' => 'alejo@gmail.com',
        ];

        $response = $this->postJson(route('register.api'), $request);

        $response->assertStatus(422);
        $data = $response->json();
        $this->assertCount(1, $data['errors']);
    }

    public function test_user_can_login(): void
    {
        $request = User::factory()->create([
            'name' => 'alejo',
            'email' => 'alejo@gmail.com',
            'password' => Hash::make('testing123!'),
        ]);

        $response = $this->postJson(route('login.api'), [
            'email' => $request['email'],
            'password' => 'testing123!',
        ]);

        $response->assertOk();
        $this->isAuthenticated();
    }

    public function test_user_can_logout(): void
    {
        $request = User::factory()->create([
            'name' => 'alejo',
            'email' => 'alejo@gmail.com',
            'password' => Hash::make('testing123!'),
        ]);

        $response = $this->postJson(route('login.api'), [
            'email' => $request['email'],
            'password' => 'testing123!',
        ]);

        $response->assertOk();
        $this->isAuthenticated();

        $response = $this->postJson(route('logout.api'));
        $response->assertOk();
    }

    /**
     * @dataProvider providers
     */
    public function test_user_can_login_with_provider(string $provider, string $endpoint): void
    {
        $abstractUser = User::factory()->create([
            'name' => 'alejo',
            'email' => 'alejo@gmail.com',
            'password' => 'testing123!',
        ]);
        $providerClass = Mockery::mock('Laravel\Socialite\Contracts\Provider');
        $providerClass->shouldReceive('user')->andReturn($abstractUser);
        $providerClass->shouldReceive('stateless')->andReturn($providerClass);
        $providerClass->shouldReceive('redirect')->andReturn($providerClass);
        $providerClass->shouldReceive('getTargetUrl')->andReturn($endpoint);

        Socialite::shouldReceive('driver')->with($provider)->andReturn($providerClass);

        $response = $this->getJson(route('api.redirect', [
            'driver' => $provider,
        ]));

        $response->assertOk();
        $this->assertEquals($endpoint, $response->content());
    }

    /**
     * @dataProvider providers
     */
    public function test_user_can_login_callback(string $provider, string $endpoint): void
    {
        $abstractUser = User::factory()->create([
            'name' => 'alejo',
            'email' => 'alejo@gmail.com',
            'password' => 'testing123!',
        ]);
        $providerClass = Mockery::mock('Laravel\Socialite\Contracts\Provider');
        $providerClass->shouldReceive('user')->andReturn($abstractUser);
        $providerClass->shouldReceive('stateless')->andReturn($providerClass);
        $providerClass->shouldReceive('redirect')->andReturn($providerClass);
        $providerClass->shouldReceive('getTargetUrl')->andReturn($endpoint);

        Socialite::shouldReceive('driver')->with($provider)->andReturn($providerClass);

        $response = $this->getJson(route('api.callback', [
            'driver' => $provider,
        ]));

        $response->assertOk();
        $this->assertNotEmpty($response->json('data')['user']);
    }

    public function providers(): array
    {
        return [
            'github' => [
                'provider' =>'github',
                'endpoint' => 'www.github.com',
            ],
            'google' => [
                'provider' =>'google',
                'endpoint' => 'www.google.com',
            ],
        ];
    }
}
