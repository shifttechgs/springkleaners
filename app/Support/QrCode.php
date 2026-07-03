<?php

namespace App\Support;

use Endroid\QrCode\Builder\Builder;

class QrCode
{
    public static function pngDataUri(string $url): string
    {
        $result = (new Builder(data: $url, size: 240, margin: 10))->build();

        return $result->getDataUri();
    }
}
