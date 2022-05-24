<?php

namespace App\Console\Commands;

use App\Models\Coin;
use App\Services\Binance;
use Illuminate\Console\Command;

class CheckAvgBigPrice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'c:checkAvgBigPrice {param}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Esse comando deverá verificar o preço médio';

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
     * @return int
     */
    public function handle()
    {

        $param_txt_uppercase = strtoupper($this->argument('param'));
        $param_txt_lowercase = strtolower($this->argument('param'));

        $coin = Coin::where('symbol', $param_txt_uppercase)->first();

        if (empty($coin->id)) {
            $this->info('symbol not found.');
            die();
        }

        $sock = stream_socket_client("tls://stream.binance.com:9443/ws/aggTrade", $error, $errnum, 30, STREAM_CLIENT_CONNECT, stream_context_create(null));

        if (!$sock) {

            echo "[$errnum] $error" . PHP_EOL;

        } else {

            fwrite($sock, "GET /stream?streams=" . $param_txt_lowercase . "@aggTrade HTTP/1.1\r\nHost: stream.binance.com:9443\r\nAccept: */*\r\nConnection: Upgrade\r\nUpgrade: websocket\r\nSec-WebSocket-Version: 13\r\nSec-WebSocket-Key: " . rand(0, 999) . "\r\n\r\n");
           
            while (!feof($sock)) {

                $explode = explode(',', fgets($sock, 230));              
                
                foreach ($explode as $key => $value) {

                    $return = Coin::verificationPrice($value, $param_txt_uppercase);
                    //echo chr(27).chr(91).'H'.chr(27).chr(91).'J'; // ^[H^[J
                    $this->info($return);
                   
                }

            }
        }
    }

}
