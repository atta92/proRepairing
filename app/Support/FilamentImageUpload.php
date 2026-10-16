<?php

namespace App\Support;

use Filament\Forms\Components\FileUpload;

class FilamentImageUpload
{
    /**
     * Category & device listing cards use a 4:3 frame on the frontend.
     */
    public static function catalogue(FileUpload $upload): FileUpload
    {
        return $upload
            ->image()
            ->disk('public')
            ->imageEditor()
            ->imageEditorAspectRatios([
                '4:3',
            ])
            ->imageCropAspectRatio('4:3')
            ->imageResizeMode('cover')
            ->imageResizeTargetWidth('800')
            ->imageResizeTargetHeight('600')
            ->imageResizeUpscale(false)
            ->helperText('Saved as 800×600 (4:3) so listing cards stay the same size.');
    }

    /**
     * Shop product cards use a square frame on the frontend.
     */
    public static function product(FileUpload $upload): FileUpload
    {
        return $upload
            ->image()
            ->disk('public')
            ->imageEditor()
            ->imageEditorAspectRatios([
                '1:1',
            ])
            ->imageCropAspectRatio('1:1')
            ->imageResizeMode('cover')
            ->imageResizeTargetWidth('800')
            ->imageResizeTargetHeight('800')
            ->imageResizeUpscale(false)
            ->helperText('Saved as 800×800 (square) so product cards stay the same size.');
    }

    /**
     * Before/after and blog covers use a landscape frame.
     */
    public static function landscape(FileUpload $upload): FileUpload
    {
        return $upload
            ->image()
            ->disk('public')
            ->imageEditor()
            ->imageEditorAspectRatios([
                '16:9',
            ])
            ->imageCropAspectRatio('16:9')
            ->imageResizeMode('cover')
            ->imageResizeTargetWidth('1200')
            ->imageResizeTargetHeight('675')
            ->imageResizeUpscale(false)
            ->helperText('Saved as 1200×675 (16:9) for a consistent landscape crop.');
    }
}
