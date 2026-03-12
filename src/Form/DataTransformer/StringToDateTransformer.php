<?php

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class StringToDateTransformer implements DataTransformerInterface
{
    public function transform($dateString): ?string
    {
        return $dateString;
    }

    
    public function reverseTransform($dateDate): ?\DateTime
    {
        return $dateDate ? new \DateTime($dateDate) : null;
    }
}