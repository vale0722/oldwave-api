<?php

namespace Tests\Feature\Transaction;

use App\Constants\TransactionStatuses;
use App\Events\PaymentUpdated;
use App\Listeners\NotifyUserListener;
use App\Models\Item;
use App\Models\ShoppingCarItem;
use App\Models\Transaction;
use App\Models\User;
use App\Services\PlacetopayServiceContract;
use Dnetix\Redirection\Message\RedirectInformation;
use Dnetix\Redirection\Message\RedirectResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Mockery;
use Symfony\Component\Console\Command\Command as CommandAlias;
use Tests\TestCase;

class ShowTransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_application_get_transaction(): void
    {
        $user = User::factory()->create([
            'name' => 'alejo',
            'email' => 'alejo@gmail.com',
            'password' => Hash::make('testing123!'),
        ]);

        $transaction = Transaction::factory()->create([
            'user_id' => $user->id,
            'currency' => 'USD',
            'document_type' => 'CC',
            'document' => '10009872345',
            'name' => 'PRUEBA',
            'email' => 'prueba@test.com',
            'city' => 'medellin',
            'address' => 'cll 80 # 23 -12',
        ]);

        $item = Item::factory()->create();

        ShoppingCarItem::factory()->create([
            'transaction_id' => $transaction->id,
            'item_id' => $item->id,
            'price' => $item->price,
            'discount' => 0,
            'count' => 2,
            'total' => $item->price * 2,
        ]);

        $response = $this->actingAs($user)->getJson(route('transactions.show', [
            'transaction' => $transaction->reference,
        ]));

        $response->assertOk();

        $data = $response->json('data');

        $this->assertNotEmpty($data['transaction']);
        $this->assertCount(1, $data['transaction']['shopping_car_items']);
    }
}
