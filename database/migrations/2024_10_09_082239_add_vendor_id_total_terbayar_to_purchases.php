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
        Schema::table('purchases', function (Blueprint $table) {
            $table->bigInteger('vendor_id')->nullable()->after('user_id');
            $table->decimal('total', 11, 2)->after('vendor_id')->comment('harga beli')->default(0);
            $table->decimal('terbayar', 11, 2)->after('total')->default(0);
            $table->date('purchase_at')->after('terbayar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn('vendor_id');
            $table->dropColumn('total');
            $table->dropColumn('terbayar');
            $table->dropColumn('purchase_at');
        });
    }
};
