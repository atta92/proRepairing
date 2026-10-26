<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('repair_addons', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        $now = now();

        DB::table('repair_addons')->insert([
            [
                'name' => 'Premium Case',
                'slug' => 'premium-case',
                'description' => 'Durable protective case fitted with your repair.',
                'price' => 9.99,
                'sort_order' => 10,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Screen Protector (Fitted)',
                'slug' => 'screen-protector-fitted',
                'description' => 'Tempered glass fitted in-store before return.',
                'price' => 9.99,
                'sort_order' => 20,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Case + Protector Bundle',
                'slug' => 'case-protector-bundle',
                'description' => 'Premium case and fitted screen protector together.',
                'price' => 18.99,
                'sort_order' => 30,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('repair_addons');
    }
};
