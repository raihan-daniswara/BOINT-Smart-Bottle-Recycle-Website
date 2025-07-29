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
        Schema::create('redeems', function (Blueprint $table) {
            $table->id();
            $table->string('voucher_code')->unique();
            $table->timestamp('redeemed_at')->nullable();
            $table->timestamp('expires_at')->nullable();

            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedBigInteger('user_deleted_id')->nullable(); // fallback jika user dihapus

            $table->foreignId('reward_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedBigInteger('reward_deleted_id')->nullable(); // fallback jika reward dihapus

            $table->integer('points_used');
            $table->enum('status', ['active', 'used', 'expired'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.  
     */
    public function down(): void
    {
        Schema::dropIfExists('redeems');
    }
};
