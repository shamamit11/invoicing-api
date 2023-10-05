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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('usercode')->unique()->index();
            $table->string('name')->nullable();
            $table->string('mobile')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('verification_code')->nullable();
            $table->boolean('email_verified')->default(0)->comment('0 = not verified, 1 = verified');
            $table->boolean('status')->default(1)->comment('0 = inactive, 1 = active');
            $table->rememberToken();
            $table->string('device_id')->nullable();
            $table->boolean('is_deleted')->default(0)->comment('0 = No, 1 = Yes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
