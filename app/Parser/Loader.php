<?php

namespace App\Parser;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client;

class Loader extends Model
{
    protected static $client = null;

    public static function loadPage($url)
    {
        if (!self::$client) {
            self::$client = new Client();
        }
        $response = self::$client->get($url);
        if ($response->getStatusCode() === 200) {
            return (string)$response->getBody();
        } else {
            throw new \Exception('Failed to load url: '.$url);
        }
    }
}
