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
        Schema::create('doku_payment_receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->nullable()->constrained('schools')->onDelete('cascade');
            $table->foreignId('school_inquiry_id')->constrained('school_inquiries')->onDelete('cascade');
            $table->string('invoice_number');
            $table->decimal('amount', 10, 2);
            $table->string('payment_status')->default('pending');
            $table->string('payment_gateway')->default('DOKU');
            $table->timestamp('payment_date')->nullable();
            $table->string('doku_transaction_id')->nullable();
            $table->json('raw_payload')->nullable();
            $table->string('package_name')->nullable();
            $table->string('school_name');
            $table->string('school_email');
            $table->timestamps();

            $table->index('invoice_number');
            $table->index('school_id');
            $table->index('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doku_payment_receipts');
    }
};
