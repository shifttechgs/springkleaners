<?php

// Smaller copies of the site's photos so phones don't download desktop-sized images (PageSpeed "Properly size
// images"). For every .avif under public/images/works (except hero/, which has its own sizes) it writes
// name-480.avif and name-800.avif when the original is wider, then records the widths in
// public/images/works/srcset.json, which App\Support\Img reads to build srcset. Nothing runs on the server.
//
//   php scripts/image-variants.php          (re-run after adding or replacing a photo)
//
// Needs PHP with GD AVIF support (PHP 8.1+).

$root = dirname(__DIR__);
$base = $root.'/public/images/works';
$widths = [480, 800];
$manifest = [];

$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($base, FilesystemIterator::SKIP_DOTS));

foreach ($files as $file) {
    $path = str_replace('\\', '/', $file->getPathname());

    if (! str_ends_with($path, '.avif') || str_contains($path, '/hero/') || preg_match('/-\d+\.avif$/', $path)) {
        continue;
    }

    $src = @imagecreatefromavif($path);

    if (! $src) {
        fwrite(STDERR, "skip (unreadable): {$path}\n");
        continue;
    }

    $w = imagesx($src);
    $h = imagesy($src);
    $made = [];

    foreach ($widths as $target) {
        if ($w <= $target * 1.15) {
            continue; // Not worth a copy barely smaller than the original.
        }

        $out = substr($path, 0, -5)."-{$target}.avif";
        $th = (int) round($h * $target / $w);
        $dst = imagecreatetruecolor($target, $th);
        imagealphablending($dst, false);
        imagesavealpha($dst, true);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $target, $th, $w, $h);
        imageavif($dst, $out, 55, 6);
        imagedestroy($dst);

        // Keep a copy only if it is actually lighter than the original.
        if (filesize($out) < filesize($path)) {
            $made[] = $target;
        } else {
            unlink($out);
        }
    }

    imagedestroy($src);

    if ($made) {
        $key = substr($path, strlen($root.'/public'));
        $manifest[$key] = ['w' => $w, 'variants' => $made];
        echo $key.' '.$w.'w -> '.implode(', ', $made)."\n";
    }
}

ksort($manifest);
file_put_contents($base.'/srcset.json', json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)."\n");
echo count($manifest)." images in srcset.json\n";
