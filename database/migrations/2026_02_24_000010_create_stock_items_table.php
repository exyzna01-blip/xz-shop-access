<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('stock_items', function (Blueprint $table) {
            $table->id();
            $table->string('service');
            $table->string('duration');
            $table->string('category');
            $table->string('devices')->default('');
            $table->string('email');
            $table->text('password'); // encrypted string
            $table->string('label')->default('');
            $table->decimal('capital_cost', 10, 2)->default(0);
            $table->enum('status', ['AVAILABLE','RESERVED','SOLD_PENDING','SOLD','REFUNDED'])->default('AVAILABLE')->index();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();

            $table->index(['service','duration','category','devices']);
            $table->index(['email']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_items');
    }
};
