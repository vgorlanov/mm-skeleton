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
        Schema::create('events_store', function (Blueprint $table) {
            $table->uuid('event_id');
            $table->string('event_name')->nullable(false)->comment('Название события');
            $table->timestamp('occurred_on', 6)->nullable(false)->comment('Дата события');
            $table->text('event')->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events_store');
    }
};
