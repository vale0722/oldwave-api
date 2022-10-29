<?php

use App\Constants\TransactionStatuses;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 30)->unique();
            $table->unsignedInteger('request_id')->nullable()->index();
            $table->foreignId('user_id');
            $table->enum('status', TransactionStatuses::values())->default(TransactionStatuses::PENDING);
            $table->text('reason')->nullable();
            $table->string('process_url')->nullable();
            $table->string('receipt')->nullable();
            $table->string('authorization')->nullable();
            $table->string('currency', 3);
            $table->string('document_type', 4);
            $table->string('document', 25);
            $table->string('name');
            $table->string('email');
            $table->string('city', 25);
            $table->string('address');
            $table->double('subtotal')->nullable();
            $table->double('total')->nullable();
            $table->string('ip')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
