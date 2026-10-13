<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('page_title')->nullable()->after('name');
        });

        Schema::table('devices', function (Blueprint $table) {
            $table->string('page_title')->nullable()->after('name');
        });

        Schema::table('accessories', function (Blueprint $table) {
            $table->string('page_title')->nullable()->after('name');
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('page_title');
        });

        Schema::table('devices', function (Blueprint $table) {
            $table->dropColumn('page_title');
        });

        Schema::table('accessories', function (Blueprint $table) {
            $table->dropColumn('page_title');
        });
    }
};
