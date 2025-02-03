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
        Schema::create('receivables_members', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('member_id');
            $table->text('description')->nullable();
            $table->decimal('total', 11, 2)->default(0);
            $table->decimal('terbayar', 11, 2)->default(0);
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receivables_members');
    }
};
