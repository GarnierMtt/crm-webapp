<?php

namespace App\Utils;

use Twig\Attribute\AsTwigFilter;  


class AppExtension
{
    #[AsTwigFilter('json_decode')]
    public function jsonDecode($str) {
        return json_decode($str);
    }
}