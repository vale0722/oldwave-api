<?php

namespace Tests\Feature\Items;

use App\Models\Item;
use App\Models\RatingItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexItemsTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_application_list_items()
    {
        Item::factory(5)->create();

        $response = $this->getJson(route('items.index'));

        $response->assertOk();

        $data = $response->json('data');

        $this->assertCount(5, $data);
    }

    public function test_the_application_list_only_items_enabled()
    {
        Item::factory(3)->create();
        Item::factory(2)->create(['enabled_at' => null]);

        $response = $this->getJson(route('items.index'));

        $response->assertOk();

        $data = $response->json('data');
        $this->assertCount(3, $data);
    }

    public function test_the_application_list_items_and_filter()
    {
        Item::factory(3)->create();
        Item::factory()->create(['name' => 'Testing Product 123']);

        $response = $this->getJson(route('items.index', [
            'search' => 'Testing',
        ]));

        $response->assertOk();

        $data = $response->json('data');
        $this->assertCount(1, $data);
    }

    public function test_the_application_list_rating_items()
    {
        $itemA = Item::factory()->create();
        $itemB = Item::factory()->create();

        RatingItem::factory(4)->create([
            'item_id' => $itemA->getKey(),
        ]);

        RatingItem::factory(10)->create();

        $response = $this->getJson(route('items.rating'));

        $response->assertOk();

        $data = $response->json('data');

        $this->assertCount(10, $data);
        $this->assertNotContains($itemB->slug, $data);

        $this->assertEquals($itemA->slug, $data[0]['slug']);
    }
}
