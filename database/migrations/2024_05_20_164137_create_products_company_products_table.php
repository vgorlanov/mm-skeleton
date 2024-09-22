<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_products', function (Blueprint $table) {
            $table->uuid();
            $table->uuid('company');
            $table->string('title');
            $table->text('body');
            $table->jsonb('params');
            $table->jsonb('images');
            $table->boolean('published')->default(false);
            $table->timestamp('date', 6)->nullable(false);
            $table->timestamp('deleted_at', 6)->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_products');
    }
};
