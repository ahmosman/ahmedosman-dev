<?php

namespace App\Service;

trait StringFormatTrait
{

    public function removeWhiteCharacters(string $text): string
    {
        return preg_replace('~[\r\n ]+~', '', $text);
    }

}