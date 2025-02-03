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
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('total', 11, 2)->after('student_id')->default(0);
            $table->decimal('terbayar', 11, 2)->after('total')->default(0);
            $table->date('order_at')->after('terbayar')->nullable();
            $table->bigInteger('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('total');
            $table->dropColumn('terbayar');
            $table->dropColumn('order_at');
            $table->dropColumn('user_id');
        });
    }
};
