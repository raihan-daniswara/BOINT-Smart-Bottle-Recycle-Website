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
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('location');
            $table->integer('capacity')->unsigned()->default(0);
            $table->integer('max_capacity')->unsigned()->default(0);
            $table->unsignedTinyInteger('status');
            $table->integer('last_active')->unsigned()->default(0);
            $table->string('current_token')->nullable();       // token pairing aktif
            $table->datetime('token_expires_at')->nullable();  // expired-nya token
            $table->foreignId('paired_user_id')->nullable()->constrained('users')->nullOnDelete(); // user yg dipair
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
