<?php

namespace Tests\Feature\Items;

use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ShowItemsTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_visit_only_product(): void
    {
        $item = Item::factory()->create();

        $response = $this->getJson(route('items.show', $item->slug));

        $response->assertOk();
    }

    public function test_register_a_visit_when_i_see_a_product(): void
    {
        $item = Item::factory()->create();

        $response = $this->getJson(route('items.show', $item->slug));

        $response->assertOk();

        $response = $this->getJson(route('items.rating'));

        $response->assertOk();

        $data = $response->json('data');

        $this->assertCount(1, $data);
    }
}
