<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('brand', 100);
            $table->string('city', 60);
            $table->string('slug', 255);
            $table->string('tumpnail')->nullable();
            $table->integer('stock');
            $table->string('currency', 3);
            $table->unsignedBigInteger('price');
            $table->decimal('discount')->nullable()->default(0);
            $table->text('description');
            $table->foreignId('seller_id');
            $table->foreignId('category_id');
            $table->timestamp('enabled_at')->nullable()->default(now());
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
