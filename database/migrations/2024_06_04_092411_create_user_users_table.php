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
        Schema::create('user_users', function (Blueprint $table) {
            $table->uuid();
            $table->jsonb('credential');
            $table->jsonb('data');
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
        Schema::dropIfExists('user_users');
    }
};
