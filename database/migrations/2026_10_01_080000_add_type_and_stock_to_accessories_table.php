<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('accessories', function (Blueprint $table) {
            $table->string('type')->default('accessories')->after('slug');
            $table->unsignedInteger('stock')->nullable()->after('compare_at_price');
        });

        DB::table('accessories')->whereNull('type')->orWhere('type', '')->update(['type' => 'accessories']);
    }

    public function down(): void
    {
        Schema::table('accessories', function (Blueprint $table) {
            $table->dropColumn(['type', 'stock']);
        });
    }
};
