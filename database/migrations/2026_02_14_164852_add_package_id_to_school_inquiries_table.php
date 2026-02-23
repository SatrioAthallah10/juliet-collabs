<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('school_inquiries', function (Blueprint $table) {
            $table->foreignId('package_id')
                  ->nullable()
                  ->constrained()
                  ->after('school_email');
        });
    }

    public function down(): void
    {
        Schema::table('school_inquiries', function (Blueprint $table) {
            $table->dropForeign(['package_id']);
            $table->dropColumn('package_id');
        });
    }
};
