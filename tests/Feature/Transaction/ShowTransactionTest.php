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

    public function test_the_application_query_transaction(): void
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
            'request_id' => '12345',
        ]);

        $providerClass = Mockery::mock(PlacetopayServiceContract::class);
        $result = unserialize('a:5:{s:9:"requestId";i:371;s:6:"status";a:4:{s:6:"status";s:7:"PENDING";s:6:"reason";s:2:"PT";s:7:"message";s:35:"La petición se encuentra pendiente";s:4:"date";s:25:"2017-05-17T15:57:44-05:00";}s:7:"request";a:15:{s:6:"locale";s:5:"es_CO";s:5:"payer";a:7:{s:8:"document";s:10:"1040035000";s:12:"documentType";s:2:"CC";s:4:"name";s:5:"Diego";s:7:"surname";s:5:"Calle";s:5:"email";s:16:"dnetix@gmail.com";s:6:"mobile";s:10:"3006108399";s:7:"address";a:4:{s:6:"street";s:15:"123 Main Street";s:4:"city";s:12:"Chesterfield";s:10:"postalCode";s:5:"63017";s:7:"country";s:2:"US";}}s:5:"buyer";a:6:{s:8:"document";s:10:"1040035000";s:12:"documentType";s:2:"CC";s:4:"name";s:5:"Aisha";s:7:"surname";s:8:"Nikolaus";s:5:"email";s:17:"dcallem88@msn.com";s:6:"mobile";s:10:"3006108300";}s:7:"payment";a:4:{s:9:"reference";s:20:"TEST_20170517_205552";s:11:"description";s:59:"Ut aut consequatur maxime doloremque iure voluptatem omnis.";s:6:"amount";a:2:{s:8:"currency";s:3:"USD";s:5:"total";s:3:"178";}s:12:"allowPartial";b:0;}s:12:"subscription";N;s:6:"fields";N;s:9:"returnUrl";s:30:"http://redirect.p2p.dev/client";s:13:"paymentMethod";N;s:9:"cancelUrl";N;s:9:"ipAddress";s:9:"127.0.0.1";s:9:"userAgent";s:104:"Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.98 Safari/537.36";s:10:"expiration";s:25:"2017-05-18T20:55:52+00:00";s:14:"captureAddress";b:0;s:10:"skipResult";b:0;s:11:"noBuyerFill";b:0;}s:7:"payment";a:1:{i:0;a:11:{s:6:"status";a:4:{s:6:"status";s:8:"REJECTED";s:6:"reason";s:2:"01";s:7:"message";s:30:"Negada, Transacción declinada";s:4:"date";s:25:"2017-05-17T15:56:37-05:00";}s:17:"internalReference";i:1447498827;s:13:"paymentMethod";s:10:"masterpass";s:17:"paymentMethodName";s:10:"MasterCard";s:6:"amount";a:3:{s:4:"from";a:2:{s:8:"currency";s:3:"USD";s:5:"total";i:178;}s:2:"to";a:2:{s:8:"currency";s:3:"COP";s:5:"total";d:511433.15999999997;}s:6:"factor";d:2873.2199999999998;}s:13:"authorization";s:6:"000000";s:9:"reference";s:20:"TEST_20170517_205552";s:7:"receipt";s:10:"1495054597";s:9:"franchise";s:5:"RM_MC";s:8:"refunded";b:0;s:15:"processorFields";a:2:{i:0;a:3:{s:7:"keyword";s:10:"lastDigits";s:5:"value";s:8:"****0206";s:9:"displayOn";s:4:"none";}i:1;a:3:{s:7:"keyword";s:2:"id";s:5:"value";s:32:"e6bc23b9f16980bc3e5422dbb6218f59";s:9:"displayOn";s:4:"none";}}}}s:12:"subscription";N;}');
        $providerClass->shouldReceive('query')->with($transaction->request_id)->andReturn(new RedirectInformation($result));

        app()->bind(PlacetopayServiceContract::class, fn ($app) => $providerClass);
        $item = Item::factory()->create();

        ShoppingCarItem::factory()->create([
            'transaction_id' => $transaction->id,
            'item_id' => $item->id,
            'price' => $item->price,
            'discount' => 0,
            'count' => 2,
            'total' => $item->price * 2,
        ]);
        $providerClass = Mockery::mock(PlacetopayServiceContract::class);
        $result = unserialize('a:5:{s:9:"requestId";i:371;s:6:"status";a:4:{s:6:"status";s:7:"PENDING";s:6:"reason";s:2:"PT";s:7:"message";s:35:"La petición se encuentra pendiente";s:4:"date";s:25:"2017-05-17T15:57:44-05:00";}s:7:"request";a:15:{s:6:"locale";s:5:"es_CO";s:5:"payer";a:7:{s:8:"document";s:10:"1040035000";s:12:"documentType";s:2:"CC";s:4:"name";s:5:"Diego";s:7:"surname";s:5:"Calle";s:5:"email";s:16:"dnetix@gmail.com";s:6:"mobile";s:10:"3006108399";s:7:"address";a:4:{s:6:"street";s:15:"123 Main Street";s:4:"city";s:12:"Chesterfield";s:10:"postalCode";s:5:"63017";s:7:"country";s:2:"US";}}s:5:"buyer";a:6:{s:8:"document";s:10:"1040035000";s:12:"documentType";s:2:"CC";s:4:"name";s:5:"Aisha";s:7:"surname";s:8:"Nikolaus";s:5:"email";s:17:"dcallem88@msn.com";s:6:"mobile";s:10:"3006108300";}s:7:"payment";a:4:{s:9:"reference";s:20:"TEST_20170517_205552";s:11:"description";s:59:"Ut aut consequatur maxime doloremque iure voluptatem omnis.";s:6:"amount";a:2:{s:8:"currency";s:3:"USD";s:5:"total";s:3:"178";}s:12:"allowPartial";b:0;}s:12:"subscription";N;s:6:"fields";N;s:9:"returnUrl";s:30:"http://redirect.p2p.dev/client";s:13:"paymentMethod";N;s:9:"cancelUrl";N;s:9:"ipAddress";s:9:"127.0.0.1";s:9:"userAgent";s:104:"Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.98 Safari/537.36";s:10:"expiration";s:25:"2017-05-18T20:55:52+00:00";s:14:"captureAddress";b:0;s:10:"skipResult";b:0;s:11:"noBuyerFill";b:0;}s:7:"payment";a:1:{i:0;a:11:{s:6:"status";a:4:{s:6:"status";s:8:"REJECTED";s:6:"reason";s:2:"01";s:7:"message";s:30:"Negada, Transacción declinada";s:4:"date";s:25:"2017-05-17T15:56:37-05:00";}s:17:"internalReference";i:1447498827;s:13:"paymentMethod";s:10:"masterpass";s:17:"paymentMethodName";s:10:"MasterCard";s:6:"amount";a:3:{s:4:"from";a:2:{s:8:"currency";s:3:"USD";s:5:"total";i:178;}s:2:"to";a:2:{s:8:"currency";s:3:"COP";s:5:"total";d:511433.15999999997;}s:6:"factor";d:2873.2199999999998;}s:13:"authorization";s:6:"000000";s:9:"reference";s:20:"TEST_20170517_205552";s:7:"receipt";s:10:"1495054597";s:9:"franchise";s:5:"RM_MC";s:8:"refunded";b:0;s:15:"processorFields";a:2:{i:0;a:3:{s:7:"keyword";s:10:"lastDigits";s:5:"value";s:8:"****0206";s:9:"displayOn";s:4:"none";}i:1;a:3:{s:7:"keyword";s:2:"id";s:5:"value";s:32:"e6bc23b9f16980bc3e5422dbb6218f59";s:9:"displayOn";s:4:"none";}}}}s:12:"subscription";N;}');
        $providerClass->shouldReceive('query')->with($transaction->request_id)->andReturn(new RedirectInformation($result));

        app()->bind(PlacetopayServiceContract::class, fn ($app) => $providerClass);
        $response = $this->actingAs($user)->putJson(route('transactions.query', [
            'transaction' => $transaction->reference,
        ]));

        $response->assertOk();
        $data = $response->json('data')[0];

        $this->assertEquals($data['id'], $transaction->id);
        $this->assertCount(1, $data['shopping_car_items']);
    }

    public function test_the_application_store_transaction(): void
    {
        $user = User::factory()->create([
            'name' => 'alejo',
            'email' => 'alejo@gmail.com',
            'password' => Hash::make('testing123!'),
        ]);

        $item = Item::factory()->create();
        $request = [
            'currency' => 'USD',
            'document_type' => 'CC',
            'document' => '10009872345',
            'name' => 'PRUEBA',
            'email' => 'prueba@test.com',
            'city' => 'medellin',
            'address' => 'cll 80 # 23 -12',
            'car' => [
                [
                    'slug' => $item->slug,
                    'count' => 3,
                ],
            ],
        ];

        $carrierResponse = new RedirectResponse([
            'requestId' => rand(0, 100000),
            'processUrl' => 'http://localhost/payment/process',
            'status' => [
                'status' => 'OK',
                'reason' => 2,
                'message' => 'Aprobada',
                'date' => '2019-03-10T12:36:36-05:00',
            ],
        ]);

        $providerClass = Mockery::mock(PlacetopayServiceContract::class);
        $providerClass->shouldReceive('request')->andReturn($carrierResponse);

        app()->bind(PlacetopayServiceContract::class, fn ($app) => $providerClass);

        $response = $this->actingAs($user)->postJson(route('transaction.store', $request));

        $response->assertOk();
        $data = $response->json('data');
        $this->assertNotEmpty($data['redirect_url']);
    }

    /**
     * @test
     */
    public function aGuestUserCanSettlementPaymentWhenHasPendingWithMethodCheckSuccessful()
    {
        Event::fake();
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
            'request_id' => '10003',
            'status' => TransactionStatuses::PENDING,
        ]);

        $this->assertTrue($transaction->isPending());
        $transaction->updated_at = now()->subDay();
        $transaction->save();
        $providerClass = Mockery::mock(PlacetopayServiceContract::class);
        $result = unserialize('a:5:{s:9:"requestId";i:360;s:6:"status";a:4:{s:6:"status";s:8:"APPROVED";s:6:"reason";s:2:"00";s:7:"message";s:42:"La petición ha sido aprobada exitosamente";s:4:"date";s:25:"2017-05-17T14:53:54-05:00";}s:7:"request";a:15:{s:6:"locale";s:5:"es_CO";s:5:"payer";a:6:{s:8:"document";s:10:"1040035000";s:12:"documentType";s:2:"CC";s:4:"name";s:7:"Leilani";s:7:"surname";s:6:"Zulauf";s:5:"email";s:17:"dcallem88@msn.com";s:6:"mobile";s:10:"3006108300";}s:5:"buyer";a:6:{s:8:"document";s:10:"1040035000";s:12:"documentType";s:2:"CC";s:4:"name";s:7:"Leilani";s:7:"surname";s:6:"Zulauf";s:5:"email";s:17:"dcallem88@msn.com";s:6:"mobile";s:10:"3006108300";}s:7:"payment";a:4:{s:9:"reference";s:20:"TEST_20170516_154231";s:11:"description";s:29:"Et et dolorem tenetur et cum.";s:6:"amount";a:2:{s:8:"currency";s:3:"USD";s:5:"total";s:3:"0.3";}s:12:"allowPartial";b:0;}s:12:"subscription";N;s:6:"fields";N;s:9:"returnUrl";s:30:"http://redirect.p2p.dev/client";s:13:"paymentMethod";N;s:9:"cancelUrl";N;s:9:"ipAddress";s:9:"127.0.0.1";s:9:"userAgent";s:104:"Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.98 Safari/537.36";s:10:"expiration";s:25:"2017-05-17T15:42:31+00:00";s:14:"captureAddress";b:0;s:10:"skipResult";b:0;s:11:"noBuyerFill";b:0;}s:7:"payment";a:1:{i:0;a:11:{s:6:"status";a:4:{s:6:"status";s:8:"APPROVED";s:6:"reason";s:2:"00";s:7:"message";s:8:"Aprobada";s:4:"date";s:25:"2017-05-16T10:43:39-05:00";}s:17:"internalReference";i:1447466623;s:13:"paymentMethod";s:6:"paypal";s:17:"paymentMethodName";s:6:"PayPal";s:6:"amount";a:3:{s:4:"from";a:2:{s:8:"currency";s:3:"USD";s:5:"total";d:0.29999999999999999;}s:2:"to";a:2:{s:8:"currency";s:3:"USD";s:5:"total";d:0.29999999999999999;}s:6:"factor";i:1;}s:13:"authorization";s:17:"2DG26929XX8381738";s:9:"reference";s:20:"TEST_20170516_154231";s:7:"receipt";s:10:"1447466623";s:9:"franchise";s:5:"PYPAL";s:8:"refunded";b:0;s:15:"processorFields";a:1:{i:0;a:3:{s:7:"keyword";s:13:"trazabilyCode";s:5:"value";s:28:"PAY-9BU08130ME378305MLENR4CI";s:9:"displayOn";s:4:"none";}}}}s:12:"subscription";N;}');
        $providerClass->shouldReceive('query')->with($transaction->request_id)->andReturn(new RedirectInformation($result));

        app()->bind(PlacetopayServiceContract::class, fn ($app) => $providerClass);
        $this->artisan('resolve:payments')->assertExitCode(CommandAlias::SUCCESS);

        Event::assertDispatched(PaymentUpdated::class, function ($event) {
            (new NotifyUserListener())->handle($event);
            return true;
        });
    }
}
