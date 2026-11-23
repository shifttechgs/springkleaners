<?php

namespace Tests\Feature;

use App\Support\Img;
use Tests\TestCase;

class ResponsiveImagesTest extends TestCase
{
    public function test_listed_images_get_a_srcset_whose_files_all_exist(): void
    {
        $manifest = json_decode((string) file_get_contents(public_path('images/works/srcset.json')), true);
        $this->assertNotEmpty($manifest);

        foreach ($manifest as $src => $entry) {
            $srcset = Img::srcset($src);
            $this->assertStringContainsString("{$src} {$entry['w']}w", $srcset);

            foreach (explode(', ', $srcset) as $candidate) {
                [$file] = explode(' ', $candidate);
                $this->assertFileExists(public_path(ltrim($file, '/')), "srcset points at a missing file: {$file}");
            }
        }
    }

    public function test_unlisted_images_get_no_srcset_attributes(): void
    {
        $this->assertSame('', Img::srcset('/images/logo-light.png'));
        $this->assertSame('', Img::attrs('/images/not-there.avif', '100vw'));
        $this->assertSame('', Img::srcset(null));
    }

    public function test_attrs_renders_srcset_and_sizes(): void
    {
        $attrs = Img::attrs('/images/works/service-pages/deep-cleaning.avif', '(min-width: 1024px) 420px, 100vw');

        $this->assertStringContainsString('srcset="/images/works/service-pages/deep-cleaning-480.avif 480w', $attrs);
        $this->assertStringContainsString('sizes="(min-width: 1024px) 420px, 100vw"', $attrs);
    }
}
