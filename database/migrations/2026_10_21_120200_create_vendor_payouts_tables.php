<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendor_payouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('GBP');
            $table->date('period_from')->nullable();
            $table->date('period_to')->nullable();
            $table->string('status')->default('due');
            $table->timestamp('paid_at')->nullable();
            $table->string('reference')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('status');
        });

        Schema::create('vendor_payout_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_payout_id')->constrained()->cascadeOnDelete();
            $table->foreignId('shop_order_item_id')->constrained()->cascadeOnDelete();
            $table->decimal('gross', 10, 2);
            $table->decimal('commission', 10, 2);
            $table->decimal('net', 10, 2);
            $table->timestamps();

            $table->unique('shop_order_item_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_payout_items');
        Schema::dropIfExists('vendor_payouts');
    }
};
