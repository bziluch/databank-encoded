<?php

namespace App\Util;

class TransformUtil
{
    public static function findAndReplaceLinks(string $body): string
    {
        preg_match('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $body, $matches);
        $body = ' '.$body.' ';
        foreach ($matches as $match) {
            $body = str_replace(' '.$match.' ', ' <a href="'.$match.'" target="_blank">'.$match.'</a> ', $body);
        }
        return $body;
    }
}