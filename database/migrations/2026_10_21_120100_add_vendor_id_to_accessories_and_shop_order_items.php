<?php

use App\Models\SiteSetting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('accessories', function (Blueprint $table) {
            $table->foreignId('vendor_id')->nullable()->after('id')->constrained()->nullOnDelete();
        });

        Schema::table('shop_order_items', function (Blueprint $table) {
            $table->foreignId('vendor_id')->nullable()->after('shop_order_id')->constrained()->nullOnDelete();
            $table->string('vendor_name')->nullable()->after('vendor_id');
            $table->decimal('commission_rate', 5, 2)->default(0)->after('quantity');
            $table->decimal('commission_amount', 10, 2)->default(0)->after('commission_rate');
            $table->decimal('vendor_net', 10, 2)->default(0)->after('commission_amount');
            $table->string('fulfilment_status')->default('pending')->after('vendor_net');
        });

        $siteName = 'Own shop';
        try {
            $settings = SiteSetting::query()->first();
            if ($settings && filled($settings->site_name ?? null)) {
                $siteName = (string) $settings->site_name;
            }
        } catch (\Throwable) {
            // settings table may not exist in fresh installs before this point
        }

        $slug = Str::slug($siteName) ?: 'own-shop';
        $baseSlug = $slug;
        $i = 1;
        while (DB::table('vendors')->where('slug', $slug)->exists()) {
            $slug = $baseSlug.'-'.$i++;
        }

        $vendorId = DB::table('vendors')->insertGetId([
            'user_id' => null,
            'name' => $siteName,
            'slug' => $slug,
            'email' => config('mail.from.address', 'shop@example.com'),
            'phone' => null,
            'description' => 'Official store.',
            'status' => 'approved',
            'commission_rate' => 0,
            'is_platform' => true,
            'country' => 'GB',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('accessories')->whereNull('vendor_id')->update(['vendor_id' => $vendorId]);

        DB::table('shop_order_items')
            ->orderBy('id')
            ->chunkById(100, function ($items) use ($vendorId, $siteName) {
                foreach ($items as $item) {
                    $lineTotal = round((float) $item->price * (int) $item->quantity, 2);
                    DB::table('shop_order_items')->where('id', $item->id)->update([
                        'vendor_id' => $vendorId,
                        'vendor_name' => $siteName,
                        'commission_rate' => 0,
                        'commission_amount' => 0,
                        'vendor_net' => $lineTotal,
                        'fulfilment_status' => 'pending',
                    ]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('shop_order_items', function (Blueprint $table) {
            $table->dropConstrainedForeignId('vendor_id');
            $table->dropColumn([
                'vendor_name',
                'commission_rate',
                'commission_amount',
                'vendor_net',
                'fulfilment_status',
            ]);
        });

        Schema::table('accessories', function (Blueprint $table) {
            $table->dropConstrainedForeignId('vendor_id');
        });
    }
};
