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
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('org_name')->nullable();
            $table->string('org_email')->nullable();
            $table->string('org_phone')->nullable();
            $table->string('org_website')->nullable();
            $table->string('org_address')->nullable();
            $table->string('org_address_1')->nullable();
            $table->string('org_address_2')->nullable();
            $table->string('org_city')->nullable();
            $table->string('org_country')->nullable();
            $table->string('org_license_no')->nullable();
            $table->string('org_logo')->nullable();
            $table->string('org_signature')->nullable();
            $table->string('org_stamp')->nullable();
            $table->string('org_trn_no')->nullable();
            $table->text('org_terms_conditions')->nullable();
            $table->string('org_currency')->nullable();
            $table->double('tax_percent')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
