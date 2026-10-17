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
            $table->json('gallery_paths')->nullable()->after('image_path');
        });

        $rows = DB::table('accessories')
            ->whereNotNull('image_path')
            ->where('image_path', '!=', '')
            ->get(['id', 'image_path']);

        foreach ($rows as $row) {
            DB::table('accessories')
                ->where('id', $row->id)
                ->update([
                    'gallery_paths' => json_encode([$row->image_path]),
                ]);
        }
    }

    public function down(): void
    {
        Schema::table('accessories', function (Blueprint $table) {
            $table->dropColumn('gallery_paths');
        });
    }
};
