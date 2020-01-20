<?php

namespace App\Observers;

use App\Parser\Finder;
use App\Parser\Proxy;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\DB;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use Spatie\Crawler\CrawlObserver;

class PageObserver extends CrawlObserver
{
    //
    /**
     * @inheritDoc
     */
    public function crawled(UriInterface $url, ResponseInterface $response, ?UriInterface $foundOnUrl = null)
    {
        $page        = (string) $response->getBody();
        $proxiesData = Finder::findProxy($page);
        foreach ($proxiesData as $proxyData) {
            $proxy = Proxy::firstOrCreate([
                'ip'   => DB::raw("inet_aton('{$proxyData[1]}')"),
                'port' => $proxyData[2]
            ]);
        }
    }

    /**
     * @inheritDoc
     */
    public function crawlFailed(UriInterface $url, RequestException $requestException, ?UriInterface $foundOnUrl = null)
    {
        xdebug_break();
        // TODO: Implement crawlFailed() method.
    }

    public function willCrawl(UriInterface $url)
    {
        parent::willCrawl($url); // TODO: Change the autogenerated stub
    }

    public function finishedCrawling()
    {
        parent::finishedCrawling(); // TODO: Change the autogenerated stub
    }
}