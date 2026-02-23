<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('school_inquiries', function (Blueprint $table) {
            $table->decimal('price', 15, 2)->nullable()->after('package_id');
            $table->string('invoice_number')->nullable()->unique()->after('price');
            $table->string('payment_status')->default('pending')->after('invoice_number');
        });
    }

    public function down(): void
    {
        Schema::table('school_inquiries', function (Blueprint $table) {
            $table->dropColumn(['price', 'invoice_number', 'payment_status']);
        });
    }
};

