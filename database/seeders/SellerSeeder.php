<?php

namespace Database\Seeders;

use App\Models\Seller;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class SellerSeeder extends Seeder
{
    public function run(): void
    {
        $sellers = collect(json_decode(Storage::disk('docs')->get(config('docs.sellers')), true));
        $sellers->each(function ($seller) {
            Seller::firstOrCreate([
                'document' => $seller['document'],
            ], $seller);
        });
    }
}
