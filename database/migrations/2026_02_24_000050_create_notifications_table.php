<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->enum('to_role', ['ADMIN'])->index();
            $table->foreignId('to_user_id')->constrained('users')->cascadeOnDelete();
            $table->text('message');
            $table->enum('type', ['NEW_DROP','SYSTEM'])->default('SYSTEM');
            $table->timestamp('read_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
