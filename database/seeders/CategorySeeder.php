<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = collect(json_decode(Storage::disk('docs')->get(config('docs.categories')), true));
        $categories->each(function ($category) {
            Category::firstOrCreate([
                'code' => $category['code'],
            ], [
                'name' => $category['name'],
                'slug' => $category['slug'],
                'enabled_at' => now(),
            ]);
        });
    }
}
