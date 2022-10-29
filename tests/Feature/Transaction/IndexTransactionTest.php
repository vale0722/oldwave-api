<?php

namespace Tests\Feature\Transaction;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class IndexTransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_application_list_only_user_transaction(): void
    {
        $user = User::factory()->create([
            'name' => 'alejo',
            'email' => 'alejo@gmail.com',
            'password' => Hash::make('testing123!'),
        ]);

        Transaction::factory(5)->create([
            'user_id' => $user->id,
            'currency' => 'USD',
            'document_type' => 'CC',
            'document' => '10009872345',
            'name' => 'PRUEBA',
            'email' => 'prueba@test.com',
            'city' => 'medellin',
            'address' => 'cll 80 # 23 -12',
        ]);
        Transaction::factory()->create();
        $response = $this->actingAs($user)->getJson(route('transactions.index'));

        $response->assertOk();

        $data = $response->json('data');

        $this->assertCount(5, $data['transactions']);
    }
}
