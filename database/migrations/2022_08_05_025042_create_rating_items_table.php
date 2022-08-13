<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('rating_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id');
            $table->string('ip')->nullable();
            $table->string('browser')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rating_items');
    }
};
