<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->string('contact_email')->nullable()->after('stripe_secret_key');
            $table->string('contact_phone')->nullable()->after('contact_email');
            $table->string('contact_hours')->nullable()->after('contact_phone');
            $table->string('contact_address')->nullable()->after('contact_hours');
            $table->text('contact_intro')->nullable()->after('contact_address');
            $table->string('about_title')->nullable()->after('contact_intro');
            $table->longText('about_body')->nullable()->after('about_title');
            $table->string('privacy_title')->nullable()->after('about_body');
            $table->longText('privacy_body')->nullable()->after('privacy_title');
            $table->string('terms_title')->nullable()->after('privacy_body');
            $table->longText('terms_body')->nullable()->after('terms_title');
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn([
                'contact_email',
                'contact_phone',
                'contact_hours',
                'contact_address',
                'contact_intro',
                'about_title',
                'about_body',
                'privacy_title',
                'privacy_body',
                'terms_title',
                'terms_body',
            ]);
        });
    }
};
