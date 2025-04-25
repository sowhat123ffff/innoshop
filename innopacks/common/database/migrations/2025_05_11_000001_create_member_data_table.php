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
        // Drop the incorrectly named table if it exists
        Schema::dropIfExists('inno_inno_member_data');
        Schema::dropIfExists('inno_member_data');
        
        if (!Schema::hasTable('member_data')) {
            Schema::create('member_data', function (Blueprint $table) {
                $table->id()->comment('ID');
                $table->unsignedInteger('customer_id')->index('customer_id')->comment('Customer ID');
                $table->json('member_data')->nullable()->comment('Member Data Information');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_data');
    }
};
