<?php

namespace Tests\Feature\Categories;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexCategoriesTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_application_list_categories()
    {
        Category::factory(5)->create();

        $response = $this->getJson(route('categories.index'));

        $response->assertOk();

        $data = $response->json('data');

        $this->assertCount(5, $data);
    }
}
