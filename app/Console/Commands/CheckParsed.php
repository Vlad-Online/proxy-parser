<?php

namespace App\Console\Commands;

use App\Jobs\CheckProxy;
use App\Parser\Proxy;
use Illuminate\Console\Command;

class CheckParsed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parser:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check proxies in database';

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
        $proxies = Proxy::all();
        foreach ($proxies as $proxy) {
            CheckProxy::dispatch([
                $proxy->ip,
                $proxy->port
            ]);
        }
    }
}
