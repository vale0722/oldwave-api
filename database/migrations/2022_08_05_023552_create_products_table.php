<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name',255);
            $table->string('brand',255);
            $table->string('city',255);
            $table->unsignedBigInteger('price');
            $table->unsignedBigInteger('discount');
            $table->text('description');
            $table->unsignedBigInteger('seller_id');
            $table->unsignedBigInteger('category_id');
            $table->timestamps();

            $table->foreign('seller_id')
                ->on('sellers')
                ->references('id');
            $table->foreign('category_id')
                ->on('categories')
                ->references('id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
