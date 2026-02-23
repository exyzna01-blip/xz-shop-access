<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sale_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_item_id')->constrained('stock_items')->cascadeOnDelete();
            $table->foreignId('admin_id')->constrained('users');
            $table->string('admin_username');
            $table->string('service');
            $table->string('duration');
            $table->string('category');
            $table->string('devices')->default('');
            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('capital_cost', 10, 2)->default(0);

            $table->enum('status', ['RESERVED_PENDING','PENDING_APPROVAL','APPROVED_SOLD','APPROVED_REFUNDED'])->index();
            $table->enum('owner_review_status', ['NEEDS_REVIEW','APPROVED','REFUNDED'])->default('NEEDS_REVIEW');
            $table->text('owner_notes')->nullable();

            $table->date('weekly_bucket')->index();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->index(['admin_id','status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_transactions');
    }
};
