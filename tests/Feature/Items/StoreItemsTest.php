<?php

namespace Tests\Feature\Items;

use App\Models\Category;
use App\Models\Seller;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Tests\TestCase;

class StoreItemsTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_store_item(): void
    {
        $category = Category::factory()->create();
        $seller = Seller::factory()->create();
        $request = [
            'name' => 'prueba',
            'slug' => Str::slug('prueba'),
            'brand' => 'marca',
            'tumpnail' => 'http://imagen.com',
            'city' => 'medellin',
            'stock' => 50,
            'currency' => 'COP',
            'price' => 55000,
            'discount' => 0,
            'description' => 'Soy una pruebaaaaaaaaaaaaaaaaaa',
            'seller' => $seller->getKey(),
            'category' => $category->getKey(),
            'images' => [UploadedFile::fake()->image('product.jpg', 500,250)->size(50)],
        ];

        $response = $this->postJson(route('items.store'), $request);

        $response->assertOk();

        $response = $this->getJson(route('items.index', [
            'search' => 'prueba',
        ]));

        $response->assertOk();

        $data = $response->json('data');
        $this->assertCount(1, $data);
    }
}
