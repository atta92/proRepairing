<?php

namespace Database\Seeders;

use App\Models\Accessory;
use App\Models\ProductReview;
use Illuminate\Database\Seeder;

class ProductReviewSeeder extends Seeder
{
    public function run(): void
    {
        $samples = [
            'clear-magsafe-case-iphone-15' => [
                ['author_name' => 'Sarah M.', 'rating' => 5, 'title' => 'Clear and solid', 'body' => 'Fits the iPhone 15 perfectly. MagSafe snaps on every time and the raised edges actually protect the camera.', 'is_verified' => true, 'days' => 12],
                ['author_name' => 'James K.', 'rating' => 4, 'title' => 'Good everyday case', 'body' => 'Looks premium and feels sturdy. Slightly yellowed after a few weeks of pocket use, but still happy with it.', 'is_verified' => false, 'days' => 8],
                ['author_name' => 'Priya R.', 'rating' => 5, 'title' => 'Great value', 'body' => 'Ordered for collection and picked it up the same day. Quality is better than expected for the price.', 'is_verified' => true, 'days' => 3],
                ['author_name' => 'Omar A.', 'rating' => 5, 'title' => 'Perfect fit', 'body' => 'Buttons feel precise and wireless charging works without removing the case.', 'is_verified' => true, 'days' => 18],
            ],
            '20w-usb-c-fast-charger' => [
                ['author_name' => 'Lucy T.', 'rating' => 5, 'title' => 'Charges fast', 'body' => 'Gets my iPhone from low battery to usable in minutes. Compact enough for travel.', 'is_verified' => true, 'days' => 6],
                ['author_name' => 'Ben W.', 'rating' => 4, 'title' => 'Does the job', 'body' => 'Solid little charger. No fancy packaging, just works.', 'is_verified' => false, 'days' => 14],
            ],
            'wireless-earbuds-pro' => [
                ['author_name' => 'Nina S.', 'rating' => 4, 'title' => 'Surprisingly good', 'body' => 'Clear calls and decent bass. Battery lasts through my commute and gym session.', 'is_verified' => true, 'days' => 9],
                ['author_name' => 'Harry L.', 'rating' => 3, 'title' => 'Okay for the price', 'body' => 'Sound is fine. Case is a bit bulky, but they stay in during runs.', 'is_verified' => false, 'days' => 21],
                ['author_name' => 'Amelia C.', 'rating' => 5, 'title' => 'Great daily driver', 'body' => 'Paired instantly and the mic is clearer than my last cheap earbuds.', 'is_verified' => true, 'days' => 4],
            ],
            'iphone-14' => [
                ['author_name' => 'Tom H.', 'rating' => 5, 'title' => 'Looks nearly new', 'body' => 'Excellent grade Midnight 128GB. Battery health was strong and the phone arrived well packed.', 'is_verified' => true, 'days' => 20],
                ['author_name' => 'Elena V.', 'rating' => 4, 'title' => 'Happy with the refurbished phone', 'body' => 'Minor micro-scratches on the frame as described for Good grade. Works perfectly and unlocked straight away.', 'is_verified' => true, 'days' => 9],
                ['author_name' => 'Daniel P.', 'rating' => 5, 'title' => 'Smooth upgrade', 'body' => 'Came from an iPhone 11. Screen is bright, Face ID works, and setup was easy.', 'is_verified' => true, 'days' => 5],
                ['author_name' => 'Grace N.', 'rating' => 5, 'title' => 'Product Red looks great', 'body' => 'Chose Excellent / Product Red. No marks I could find. Would buy refurbished again.', 'is_verified' => true, 'days' => 2],
                ['author_name' => 'Marcus J.', 'rating' => 4, 'title' => 'Fair grade as expected', 'body' => 'Visible wear on the corners but camera and battery are solid. Honest grading.', 'is_verified' => false, 'days' => 27],
            ],
            'iphone-13' => [
                ['author_name' => 'Sophie B.', 'rating' => 5, 'title' => 'Best value Apple phone', 'body' => 'Still feel fast in 2026. Bought Good / Midnight and it looks better than expected.', 'is_verified' => true, 'days' => 11],
                ['author_name' => 'Ryan D.', 'rating' => 4, 'title' => 'Solid refurbished buy', 'body' => 'Battery at a healthy level and unlocked for my SIM same day.', 'is_verified' => true, 'days' => 16],
                ['author_name' => 'Chloe F.', 'rating' => 5, 'title' => 'Pink is lovely', 'body' => 'Exact colour I wanted. Packaging was secure and warranty gave peace of mind.', 'is_verified' => true, 'days' => 7],
            ],
            'iphone-12' => [
                ['author_name' => 'Adam R.', 'rating' => 4, 'title' => 'Cheap 5G iPhone', 'body' => 'Does everything I need. OLED still looks great for the money.', 'is_verified' => true, 'days' => 13],
                ['author_name' => 'Mia K.', 'rating' => 5, 'title' => 'Surprisingly clean', 'body' => 'Excellent white 64GB. Hardly any wear. Happy customer.', 'is_verified' => false, 'days' => 22],
            ],
            'iphone-15' => [
                ['author_name' => 'Jordan E.', 'rating' => 5, 'title' => 'USB-C at last', 'body' => 'Finally one cable for phone and laptop. Excellent condition, camera is superb.', 'is_verified' => true, 'days' => 8],
                ['author_name' => 'Isabelle M.', 'rating' => 5, 'title' => 'Worth the upgrade', 'body' => 'Dynamic Island is useful and the phone feels premium. Verified purchase badge made me trust it.', 'is_verified' => true, 'days' => 3],
                ['author_name' => 'Leo S.', 'rating' => 4, 'title' => 'Almost perfect', 'body' => 'Tiny mark near the USB-C port that was disclosed. Otherwise excellent.', 'is_verified' => true, 'days' => 19],
            ],
            'samsung-galaxy-s22' => [
                ['author_name' => 'Katie L.', 'rating' => 4, 'title' => 'Great Android flagship', 'body' => 'Screen is gorgeous. Good grade had light wear only. Unlocked for Three with no fuss.', 'is_verified' => true, 'days' => 10],
                ['author_name' => 'Noah B.', 'rating' => 5, 'title' => 'Fast delivery', 'body' => 'Ordered online, collected next day. Battery lasts a full day for me.', 'is_verified' => true, 'days' => 6],
            ],
            'samsung-galaxy-s23' => [
                ['author_name' => 'Freya H.', 'rating' => 5, 'title' => 'Compact powerhouse', 'body' => 'Love the size. Cream Excellent looks brand new. Cameras are excellent in low light.', 'is_verified' => true, 'days' => 4],
                ['author_name' => 'Owen C.', 'rating' => 4, 'title' => 'Strong performer', 'body' => 'Smooth one UI experience. Minor frame polish marks on Good grade as expected.', 'is_verified' => true, 'days' => 15],
                ['author_name' => 'Zara P.', 'rating' => 5, 'title' => 'Would recommend', 'body' => 'Staff explained grades clearly and the phone works flawlessly.', 'is_verified' => false, 'days' => 28],
            ],
            'google-pixel-7' => [
                ['author_name' => 'Ethan G.', 'rating' => 5, 'title' => 'Best camera for the money', 'body' => 'Photos are incredible. Clean Android with no bloat. Very happy.', 'is_verified' => true, 'days' => 9],
                ['author_name' => 'Hannah W.', 'rating' => 4, 'title' => 'Nice daily phone', 'body' => 'Snow Excellent looks clean. Software updates are a big plus.', 'is_verified' => true, 'days' => 17],
            ],
            'google-pixel-8' => [
                ['author_name' => 'Ibrahim Y.', 'rating' => 5, 'title' => 'Future-proof Pixel', 'body' => 'Hazel Excellent is stunning. AI features are useful and battery is solid.', 'is_verified' => true, 'days' => 5],
                ['author_name' => 'Poppy A.', 'rating' => 4, 'title' => 'Great refurbished deal', 'body' => 'Saved a lot vs new. Only tiny polish mark near the frame.', 'is_verified' => true, 'days' => 12],
            ],
            'macbook-air-13' => [
                ['author_name' => 'Chris P.', 'rating' => 5, 'title' => 'M2 Air is excellent', 'body' => 'Chose the M2 / 8GB / 256GB option. Quiet, fast, and battery lasts all day for work.', 'is_verified' => true, 'days' => 15],
                ['author_name' => 'Olivia R.', 'rating' => 5, 'title' => 'Silent and light', 'body' => 'Perfect student laptop. Keyboard feels great and screen is sharp.', 'is_verified' => true, 'days' => 7],
                ['author_name' => 'Jake M.', 'rating' => 4, 'title' => 'M1 still flying', 'body' => 'Bought the M1 Good grade. Plenty of power for browsing, docs and Zoom.', 'is_verified' => false, 'days' => 24],
                ['author_name' => 'Ava Q.', 'rating' => 5, 'title' => '16GB was worth it', 'body' => 'Went for M2 / 16GB / 512GB. Multitasking is effortless.', 'is_verified' => true, 'days' => 2],
            ],
            'macbook-pro-14' => [
                ['author_name' => 'Sebastian V.', 'rating' => 5, 'title' => 'Pro screen is unreal', 'body' => 'Editing video is smooth. M1 Pro / 16GB feels like a new machine.', 'is_verified' => true, 'days' => 11],
                ['author_name' => 'Nadia F.', 'rating' => 4, 'title' => 'Workhorse laptop', 'body' => 'Ports are handy and fans are quiet under light load. Excellent condition overall.', 'is_verified' => true, 'days' => 18],
            ],
            'dell-xps-13' => [
                ['author_name' => 'Callum S.', 'rating' => 4, 'title' => 'Nice Windows ultrabook', 'body' => 'Sharp display and good keyboard. Ideal for uni work.', 'is_verified' => true, 'days' => 14],
            ],
            'ipad-9th-gen-wi-fi' => [
                ['author_name' => 'Emily J.', 'rating' => 5, 'title' => 'Perfect for kids / school', 'body' => 'Bright screen, simple to use, and the price was right. Good grade looked tidy.', 'is_verified' => true, 'days' => 8],
                ['author_name' => 'George T.', 'rating' => 4, 'title' => 'Does the basics well', 'body' => 'Streaming and note-taking are fine. Battery lasts ages.', 'is_verified' => false, 'days' => 20],
            ],
            'ipad-air-5-wi-fi' => [
                ['author_name' => 'Ruby D.', 'rating' => 5, 'title' => 'M1 iPad is quick', 'body' => 'Drawing and multitasking feel premium. Starlight Excellent looks new.', 'is_verified' => true, 'days' => 6],
                ['author_name' => 'Nathan K.', 'rating' => 5, 'title' => 'Best tablet I have owned', 'body' => 'USB-C and the display make this a no-brainer vs older iPads.', 'is_verified' => true, 'days' => 13],
            ],
            'samsung-galaxy-tab-s8' => [
                ['author_name' => 'Liam O.', 'rating' => 4, 'title' => 'Great with S Pen', 'body' => 'Notes are smooth and the screen is vivid. Happy with Good grade.', 'is_verified' => true, 'days' => 10],
            ],
            'apple-watch-se-gps-40mm' => [
                ['author_name' => 'Maya S.', 'rating' => 5, 'title' => 'Everyday fitness watch', 'body' => 'Tracks walks and sleep well. Midnight Good looked clean and included a band.', 'is_verified' => true, 'days' => 9],
                ['author_name' => 'Finn B.', 'rating' => 4, 'title' => 'Does what I need', 'body' => 'Notifications are handy. Battery lasts about a day and a half for me.', 'is_verified' => true, 'days' => 16],
            ],
            'apple-watch-series-8-gps-41mm' => [
                ['author_name' => 'Ellie C.', 'rating' => 5, 'title' => 'Love the always-on screen', 'body' => 'Feels premium on the wrist. Excellent Starlight was spotless.', 'is_verified' => true, 'days' => 5],
            ],
            'samsung-galaxy-watch-5-40mm' => [
                ['author_name' => 'Harvey N.', 'rating' => 4, 'title' => 'Solid Samsung watch', 'body' => 'Health tracking is detailed. Pairs well with my Galaxy phone.', 'is_verified' => false, 'days' => 21],
            ],
            'tempered-glass-screen-protector' => [
                ['author_name' => 'Alice P.', 'rating' => 5, 'title' => 'Free fitting is brilliant', 'body' => 'They fitted it in store with no bubbles. Peace of mind for not much money.', 'is_verified' => true, 'days' => 4],
                ['author_name' => 'Reece M.', 'rating' => 5, 'title' => 'Feels like bare glass', 'body' => 'Touch is still smooth and it already saved me from one drop scratch.', 'is_verified' => true, 'days' => 11],
            ],
            '65w-gan-laptop-charger' => [
                ['author_name' => 'Sophie L.', 'rating' => 5, 'title' => 'Tiny but powerful', 'body' => 'Charges my MacBook Air and phone. Much smaller than the original brick.', 'is_verified' => true, 'days' => 7],
            ],
        ];

        foreach ($samples as $slug => $reviews) {
            $product = Accessory::query()->where('slug', $slug)->first();

            if (! $product) {
                continue;
            }

            foreach ($reviews as $review) {
                $createdAt = now()->subDays((int) $review['days']);

                ProductReview::query()->updateOrCreate(
                    [
                        'accessory_id' => $product->id,
                        'author_name' => $review['author_name'],
                        'title' => $review['title'],
                    ],
                    [
                        'author_email' => null,
                        'rating' => $review['rating'],
                        'body' => $review['body'],
                        'is_approved' => true,
                        'is_verified' => (bool) ($review['is_verified'] ?? false),
                        'created_at' => $createdAt,
                        'updated_at' => $createdAt,
                    ]
                );
            }
        }
    }
}
