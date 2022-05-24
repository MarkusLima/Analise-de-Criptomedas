<?php

namespace App\Console\Commands;

use App\Models\Coin;
use App\ServiceAPI\Binance;
use Illuminate\Console\Command;

class SaveBidPrice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'c:saveBidPriceOnDataBase {param?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Esse comando salvará os dados de preço na base de dados com base na criptomoeda informada';

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

        if (!empty($this->argument('param'))) {

            $param_txt = strtoupper($this->argument('param'));
            $api = new Binance('api/v1/ticker/bookTicker?symbol='.$param_txt.'');
            $binance = $api->getPriceForSymbol();
            $result = Coin::saveToDataBase($binance);
            $this->info('Data has been saved.');
            dd($result);


        } else {

            if ($this->confirm('Missed the symbol parameter, want to continue?')) {
                $api = new Binance('api/v1/ticker/price');
                $binance = $api->getPriceAll();
                $result = Coin::saveAllToDataBase($binance);
                $this->info('All data has been saved.');
                dd($result);
            }

        }

    }
}
