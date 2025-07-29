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
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->string('submission_code')->unique();
            $table->datetime('submission_date')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedBigInteger('user_deleted_id')->nullable();
            $table->foreignId('device_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedBigInteger('device_deleted_id')->nullable();
            $table->integer('bottle_count');
            $table->integer('points_earned');
            $table->enum('status', ['completed', 'failed', 'pending'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
