<?php

namespace App\Jobs;

use App\Parser\Proxy;
use App\Parser\ProxyChecker;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
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
        $types   = isset($this->proxyData[2]) ? Arr::wrap($this->proxyData[2]) : [
            'http',
            'socks4',
            'socks5'
        ];
        $checker = new ProxyChecker(config('parser.checkUrl'), ['timeout' => 10]);
        foreach ($types as $type) {
            $proxyString = $this->proxyData[0].':'.$this->proxyData[1].','.$type;
            $data        = [
                'ip'   => DB::raw("inet_aton('{$this->proxyData[0]}')"),
                'port' => $this->proxyData[1],
                'type' => $type
            ];
            $proxy       = Proxy::where($data)->first();
            try {
                $result = $checker->checkProxy($proxyString);
                //echo $proxyString." GOOD!\r\n";
                if (!$proxy) {
                    $data['level'] = $result['proxy_level'];
                    $proxy         = Proxy::create($data);
                }
                Log::notice($proxyString." GOOD!");
                break;
            } catch (\Exception $e) {
                //echo $proxyString.' '.$e->getMessage()."\r\n";
                if ($proxy) {
                    $proxy->alive = 0;
                    $proxy->save();
                }
                Log::notice($proxyString.' '.$e->getMessage());
            }

        }
    }
}
