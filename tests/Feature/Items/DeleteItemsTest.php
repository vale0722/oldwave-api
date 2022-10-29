<?php

namespace Tests\Feature\Items;

use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteItemsTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_delete_a_item(): void
    {
        $item = Item::factory()->create();

        $this->deleteJson(route('items.delete', $item));

        $response = $this->getJson(route('items.index'));

        $response->assertOk();

        $data = $response->json('data');

        $this->assertCount(0, $data);
    }
}
