<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $posts = [
            [
                'title' => 'How to tell if your iPhone screen needs replacing',
                'excerpt' => 'Cracks, dead pixels, touch ghosting — a quick guide to spotting when a screen repair is the right call.',
                'body' => '<p>A cracked screen is easy to spot, but not every display fault means a full replacement. Dead pixels, colour banding, unresponsive touch zones and backlight bleed can all mean the LCD or OLED panel is failing even when the glass looks fine.</p><p>If the glass is cracked but the display still looks perfect and touch works normally, a glass-only repair may be possible on some models. For most modern iPhones, cracked glass and display damage are repaired together as a single assembly.</p><p>Bring it in for a free diagnostic — we’ll show you what’s wrong and quote a fixed price before any work starts.</p>',
            ],
            [
                'title' => 'Battery health under 80%: when to replace it',
                'excerpt' => 'Sudden shutdowns and short battery life usually mean your cell is worn. Here’s what the percentage actually means.',
                'body' => '<p>Phone batteries wear with charge cycles. Once health drops below about 80%, you may notice unexpected shutdowns, slower performance, or needing the charger mid-afternoon.</p><p>Replacing the battery restores day-long use and is one of the most cost-effective repairs we do. We use quality cells with the same warranty as our other work, and most phones are ready the same day.</p><p>Check Settings → Battery → Battery Health on iPhone, or use our free in-store test for Android.</p>',
            ],
            [
                'title' => 'Mail-in repair vs walk-in: which should you choose?',
                'excerpt' => 'Same technicians, same warranty — whether you post it free or pop into the store.',
                'body' => '<p>Walk-in is ideal if you’re nearby and want a same-day screen or battery. Mail-in works just as well from anywhere in the UK: book online, pack the device, and post it with the label we email you.</p><p>Either way you get published prices, in-house technicians and a 12-month warranty. We never factory-reset your device unless you ask us to.</p>',
            ],
            [
                'title' => 'What to do if your phone gets wet',
                'excerpt' => 'Act fast — power off, dry carefully, and skip the rice. Here’s the safe next step.',
                'body' => '<p>Water damage gets worse the longer the device stays powered on. Switch it off immediately, remove any case, and do not charge it. Avoid rice or a hairdryer — both can push moisture deeper or leave dust behind.</p><p>Bring it in for a free diagnostic as soon as you can. Early board cleaning often saves the phone and costs less than a full motherboard repair later.</p>',
            ],
            [
                'title' => 'Charging port not working? Try these checks first',
                'excerpt' => 'Lint, bent pins or a failing cable — a quick triage before you book a port repair.',
                'body' => '<p>If your phone only charges at certain angles, clean the port gently with a wooden or plastic toothpick — never metal. Test with a known-good cable and charger too.</p><p>When it still will not charge, the port or board-side connector may need replacing. Most phones are same-day jobs once we confirm the fault.</p>',
            ],
        ];

        foreach ($posts as $index => $post) {
            Post::query()->updateOrCreate(
                ['slug' => Str::slug($post['title'])],
                [
                    'title' => $post['title'],
                    'excerpt' => $post['excerpt'],
                    'body' => $post['body'],
                    'author_name' => 'Repairing Team',
                    'is_published' => true,
                    'published_at' => now()->subDays(count($posts) - $index),
                ]
            );
        }
    }
}
