<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Company\Domain\Status;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('company_companies', function (Blueprint $table) {
            $table->uuid();
            $table->string('name', 255);
            $table->jsonb('about');
            $table->jsonb('contacts');
            $table->jsonb('information');
            $table->jsonb('statuses');
            $table->text('status')->nullable(false)->default(Status::NEW->value);
            $table->timestamp('date', 6)->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_companies');
    }
};
