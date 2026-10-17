<?php

namespace App\Support;

use Filament\Forms\Components\FileUpload;

class FilamentImageUpload
{
    /**
     * Store the full photo. The storefront uses a fixed frame + object-cover
     * (centre crop) — normal e-commerce practice for mixed aspect ratios.
     */
    public static function catalogue(FileUpload $upload): FileUpload
    {
        return $upload
            ->image()
            ->disk('public')
            ->imageResizeMode('contain')
            ->imageResizeTargetWidth('1600')
            ->imageResizeUpscale(false)
            ->helperText('Tip: use a 4:3 photo on a plain background for the cleanest cards. The site fills a fixed 4:3 frame from the centre.');
    }

    public static function product(FileUpload $upload, bool $multiple = false): FileUpload
    {
        $upload = $upload
            ->image()
            ->disk('public')
            ->imageResizeMode('contain')
            ->imageResizeTargetWidth('1600')
            ->imageResizeUpscale(false);

        if ($multiple) {
            return $upload
                ->multiple()
                ->reorderable()
                ->maxFiles(12)
                ->panelLayout('grid')
                ->helperText('Up to 12 photos. Tip: square (1:1) on a plain background looks best. First image is the listing photo.');
        }

        return $upload
            ->helperText('Tip: square (1:1) on a plain background looks best on product cards.');
    }

    public static function landscape(FileUpload $upload): FileUpload
    {
        return $upload
            ->image()
            ->disk('public')
            ->imageResizeMode('contain')
            ->imageResizeTargetWidth('1600')
            ->imageResizeUpscale(false)
            ->helperText('Tip: 16:9 landscape works best here.');
    }
}
