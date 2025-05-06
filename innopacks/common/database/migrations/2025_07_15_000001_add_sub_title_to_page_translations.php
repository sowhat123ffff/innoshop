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
        Schema::table('page_translations', function (Blueprint $table) {
            if (!Schema::hasColumn('page_translations', 'sub_title')) {
                $table->string('sub_title')->nullable()->after('title')->comment('Sub Title');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('page_translations', function (Blueprint $table) {
            if (Schema::hasColumn('page_translations', 'sub_title')) {
                $table->dropColumn('sub_title');
            }
        });
    }
};
