<?php

if (! extension_loaded('gd')) {
    fwrite(STDERR, "GD extension required\n");
    exit(1);
}

$s = 180;
$image = imagecreatetruecolor($s, $s);
$bg = imagecolorallocate($image, 15, 23, 32);
$accent = imagecolorallocate($image, 13, 148, 136);
$light = imagecolorallocate($image, 20, 184, 166);

imagefilledrectangle($image, 0, 0, $s - 1, $s - 1, $bg);
imagefilledellipse($image, 90, 90, 100, 100, $accent);
imagefilledrectangle($image, 52, 118, 128, 138, $light);

$out = __DIR__ . '/../public/apple-touch-icon.png';
imagepng($image, $out);
imagedestroy($image);

echo "Generated {$out}\n";
