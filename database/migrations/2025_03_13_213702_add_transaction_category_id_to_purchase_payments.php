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
        Schema::table('purchase_payments', function (Blueprint $table) {
            $table->foreignId('transaction_category_id')->default(0);
            $table->foreignId('month_period_id')->default(0);
            $table->foreignId('year_period_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_payments', function (Blueprint $table) {
            $table->dropColumn('transaction_category_id');
            $table->dropColumn('month_period_id');
            $table->dropColumn('year_period_id');
        });
    }
};
