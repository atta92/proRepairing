<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->string('site_name')->nullable()->after('stripe_secret_key');
            $table->string('site_logo_path')->nullable()->after('site_name');
            $table->text('site_description')->nullable()->after('site_logo_path');
            $table->string('social_facebook')->nullable()->after('contact_intro');
            $table->string('social_instagram')->nullable()->after('social_facebook');
            $table->string('social_twitter')->nullable()->after('social_instagram');
            $table->string('social_youtube')->nullable()->after('social_twitter');
            $table->string('social_linkedin')->nullable()->after('social_youtube');
            $table->string('social_tiktok')->nullable()->after('social_linkedin');
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn([
                'site_name',
                'site_logo_path',
                'site_description',
                'social_facebook',
                'social_instagram',
                'social_twitter',
                'social_youtube',
                'social_linkedin',
                'social_tiktok',
            ]);
        });
    }
};
