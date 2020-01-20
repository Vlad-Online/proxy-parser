<?php

namespace App\Jobs;

use App\Parser\Proxy;
use App\Parser\ProxyChecker;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckProxy implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $proxyData;

    /**
     * Create a new job instance.
     *
     * @param $proxyData
     */
    public function __construct($proxyData)
    {
        $this->proxyData = $proxyData;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $types   = [
            'http',
            'socks4',
            'socks5'
        ];
        $checker = new ProxyChecker(config('parser.checkUrl'), ['timeout' => 10]);
        foreach ($types as $type) {
            try {
                $proxyString = $this->proxyData[0].':'.$this->proxyData[1].','.$type;
                $result      = $checker->checkProxy($proxyString);
                echo $proxyString." GOOD!\r\n";
                $proxy = Proxy::firstOrCreate([
                    'ip'   => DB::raw("inet_aton('{$this->proxyData[0]}')"),
                    'port' => $this->proxyData[1],
                    'type' => $type,
                    'level' => $result['proxy_level']
                ]);
            } catch (\Exception $e) {
                echo $proxyString.' '.$e->getMessage()."\r\n";
                //Log::notice($proxyString.' '.$e->getMessage());
            }

        }
    }
}
