<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('pathFileFlags', [$this, 'pathFileFlags']),
        ];
    }

    public function pathFileFlags(string $file): string
    {
        $pathFile = "/uploads/flags/". $file;

        return $pathFile;
    }
}