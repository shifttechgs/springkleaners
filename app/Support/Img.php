<?php

namespace App\Support;

/**
 * srcset for photos that have smaller copies made by `php scripts/image-variants.php` (listed in
 * public/images/works/srcset.json). Returns '' for anything not listed, so the plain src is used as before.
 */
class Img
{
    private static ?array $manifest = null;

    public static function srcset(?string $src): string
    {
        if (blank($src)) {
            return '';
        }

        self::$manifest ??= self::load();
        $entry = self::$manifest[$src] ?? null;

        if (! $entry) {
            return '';
        }

        $stem = substr($src, 0, -strlen('.avif'));
        $parts = array_map(fn (int $w) => "{$stem}-{$w}.avif {$w}w", $entry['variants']);
        $parts[] = "{$src} {$entry['w']}w";

        return implode(', ', $parts);
    }

    /** ` srcset="…" sizes="…"` ready to drop into an <img>, or '' when the image has no smaller copies. */
    public static function attrs(?string $src, string $sizes): string
    {
        $srcset = self::srcset($src);

        return $srcset === '' ? '' : ' srcset="'.e($srcset).'" sizes="'.e($sizes).'"';
    }

    private static function load(): array
    {
        $file = public_path('images/works/srcset.json');

        return is_file($file) ? (json_decode((string) file_get_contents($file), true) ?: []) : [];
    }
}
