<?php

namespace App\Parser;

use Illuminate\Database\Eloquent\Model;

class Finder extends Model
{
    public static function findProxy($body)
    {
        if (preg_match_all('/(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}).*?>(\d{2,5})</is', $body, $matches,
            PREG_SET_ORDER)) {
            return $matches;
        } elseif (preg_match_all('/(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})[^\d]{1,20}(\d{2,5})/is', $body, $matches,
            PREG_SET_ORDER)) {
            return $matches;
        } else {
            return [];
        }
    }

    public static function findUrl($body, $baseUrl)
    {
        if (preg_match_all('/<a[^>]*href="(.*?)"[^>]*>/is', $body, $matches)) {
            return $matches;
        } else {
            return [];
        }
    }
}
