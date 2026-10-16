<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accessory_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('accessory_id')->constrained('accessories')->cascadeOnDelete();
            $table->string('sku')->nullable();
            $table->string('storage')->nullable();
            $table->string('color')->nullable();
            $table->string('condition')->nullable();
            $table->string('unlock_status')->nullable();
            $table->string('ram')->nullable();
            $table->string('cpu')->nullable();
            $table->string('screen_size')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('compare_at_price', 10, 2)->nullable();
            $table->unsignedInteger('stock')->nullable();
            $table->string('image_path')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['accessory_id', 'is_active']);
        });

        Schema::table('shop_order_items', function (Blueprint $table) {
            $table->foreignId('accessory_variant_id')
                ->nullable()
                ->after('accessory_id')
                ->constrained('accessory_variants')
                ->nullOnDelete();
            $table->string('options_label')->nullable()->after('name');
        });
    }

    public function down(): void
    {
        Schema::table('shop_order_items', function (Blueprint $table) {
            $table->dropConstrainedForeignId('accessory_variant_id');
            $table->dropColumn('options_label');
        });

        Schema::dropIfExists('accessory_variants');
    }
};
