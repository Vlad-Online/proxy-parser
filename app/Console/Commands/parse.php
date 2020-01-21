<?php

namespace App\Console\Commands;

use App\Observers\PageObserver;
use App\Parser\Finder;
use App\Parser\Loader;
use App\Parser\Proxy;
use GuzzleHttp\RequestOptions;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Spatie\Crawler\Crawler;
use Spatie\Crawler\CrawlSubdomains;

class parse extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parser:start {baseUrl?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start parsing sources for proxies';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $sources  = $this->argument('baseUrl') ? Arr::wrap($this->argument('baseUrl')) : config('parser.sources');
        $observer = new PageObserver();
        foreach ($sources as $source) {
            Crawler::create([
                RequestOptions::ALLOW_REDIRECTS => [
                    'track_redirects' => true,
                ]
            ])
                ->addCrawlObserver($observer)
                ->setUserAgent('Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.88 Safari/537.36')
                ->ignoreRobots()
                /*->setMaximumDepth(5)*/
                ->executeJavaScript()
                ->setCrawlProfile(new CrawlSubdomains($source))
                ->startCrawling($source);
        }
    }
}
