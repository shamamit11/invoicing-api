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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('invoice_code')->unique()->index();
            $table->string('invoice_no')->nullable();
            $table->timestampTz('date')->nullable();
            $table->double('total_amount')->nullable();
            $table->double('tax_percent')->nullable();
            $table->double('total_tax')->nullable();
            $table->boolean('send_email')->nullable()->default(0)->comment('0 = hide, 1 = show');
            $table->double('total_paid_amount')->nullable();
            $table->text('terms_conditions')->nullable();
            $table->integer('is_deleted')->nullable()->default(0);
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('customer_id')->references('id')->on('customers')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
