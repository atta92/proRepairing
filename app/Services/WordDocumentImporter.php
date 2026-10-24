<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\Element\TextBreak;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\Element\Title;
use PhpOffice\PhpWord\IOFactory;
use RuntimeException;

class WordDocumentImporter
{
    /**
     * @return array{html: string, plain: string, title: string|null}
     */
    public function import(string|UploadedFile|TemporaryUploadedFile $file): array
    {
        $path = $this->resolvePath($file);

        if (! is_file($path)) {
            throw new RuntimeException('Uploaded Word file could not be read.');
        }

        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        if (! in_array($extension, ['docx', 'doc'], true)) {
            throw new RuntimeException('Please upload a .docx or .doc Word file.');
        }

        $phpWord = IOFactory::load($path);

        $blocks = [];
        $plainParts = [];
        $title = null;

        foreach ($phpWord->getSections() as $section) {
            foreach ($section->getElements() as $element) {
                if ($element instanceof Title) {
                    $text = $this->elementToPlain($element);
                    if ($text === '') {
                        continue;
                    }
                    $title ??= $text;
                    $level = max(1, min(6, (int) ($element->getDepth() ?: 1)));
                    $blocks[] = '<h'.$level.'>'.e($text).'</h'.$level.'>';
                    $plainParts[] = $text;

                    continue;
                }

                $text = $this->elementToPlain($element);
                if ($text === '') {
                    if ($element instanceof TextBreak) {
                        continue;
                    }

                    continue;
                }

                $blocks[] = '<p>'.nl2br(e($text)).'</p>';
                $plainParts[] = $text;
            }
        }

        $html = trim(implode("\n", $blocks));
        $plain = trim(implode("\n\n", $plainParts));

        if ($html === '' && $plain === '') {
            throw new RuntimeException('No readable text was found in that Word file.');
        }

        if ($html === '') {
            $html = collect(preg_split("/\n{2,}/", $plain) ?: [])
                ->map(fn (string $paragraph) => '<p>'.nl2br(e(trim($paragraph))).'</p>')
                ->implode("\n");
        }

        return [
            'html' => $html,
            'plain' => $plain,
            'title' => $title,
        ];
    }

    protected function resolvePath(string|UploadedFile|TemporaryUploadedFile $file): string
    {
        if ($file instanceof TemporaryUploadedFile || $file instanceof UploadedFile) {
            return $file->getRealPath() ?: $file->getPathname();
        }

        try {
            $localPath = Storage::disk('local')->path($file);
            if (is_file($localPath)) {
                return $localPath;
            }
        } catch (\Throwable) {
            // Fall through to path candidates.
        }

        $candidates = [
            $file,
            storage_path('app/private/'.$file),
            storage_path('app/public/'.$file),
            storage_path('app/livewire-tmp/'.$file),
            storage_path('app/'.$file),
        ];

        foreach ($candidates as $candidate) {
            if (is_file($candidate)) {
                return $candidate;
            }
        }

        return $file;
    }

    protected function elementToPlain(mixed $element): string
    {
        if ($element instanceof Text) {
            return trim((string) $element->getText());
        }

        if ($element instanceof TextRun || $element instanceof Title) {
            $parts = [];
            foreach ($element->getElements() as $child) {
                $part = $this->elementToPlain($child);
                if ($part !== '') {
                    $parts[] = $part;
                }
            }

            return trim(implode(' ', $parts));
        }

        if (method_exists($element, 'getText')) {
            $text = $element->getText();
            if (is_string($text)) {
                return trim($text);
            }
            if (is_object($text) && method_exists($text, 'getText')) {
                return trim((string) $text->getText());
            }
        }

        return '';
    }

    public function excerptFromPlain(string $plain, int $max = 280): string
    {
        $plain = trim(preg_replace('/\s+/', ' ', $plain) ?? '');

        return Str::limit($plain, $max, '');
    }
}
