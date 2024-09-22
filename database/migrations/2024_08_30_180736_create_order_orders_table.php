<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_orders', function (Blueprint $table) {
            $table->uuid();
            $table->uuid('company');
            $table->uuid('customer');
            $table->jsonb('products');
            $table->jsonb('statuses');
            $table->string('status');
            $table->timestamp('date', 6)->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_orders');
    }
};
