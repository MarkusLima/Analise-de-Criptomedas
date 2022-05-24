<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coin extends Model
{
    use HasFactory;

    protected $fillable = [
        'symbol',
        'bid_price'
    ];

    static public function saveAllToDataBase($body)
    {

        if (!empty($body)) {

            foreach ($body as $value) {
                Coin::create([
                    'symbol' => $value->symbol,
                    'bid_price' => $value->price
                ]);
            }

            return [
                'msg' => 'save to database!',
                'success' => true
            ];
        } else {

            return [
                'msg' => 'data not found!',
                'success' => false
            ];
        }
    }

    static public function saveToDataBase($body)
    {

        if (!empty($body->symbol)) {

            Coin::create([
                'symbol' => $body->symbol,
                'bid_price' => $body->bidPrice
            ]);

            return [
                'msg' => 'save to database!',
                'success' => true
            ];
        } else {

            return [
                'msg' => 'data not found!',
                'success' => false
            ];
        }
    }

    static public function verificationPrice($val, $symbol)
    {
        if (str_contains($val, '"p":"')) {
            $price = explode(':', $val);
            $price = floatval(str_replace('"', '', $price[1]));

            $sum = 0;
            $count = 0;

            $coins = Coin::where('symbol', $symbol)->orderBy('id', 'desc')->limit(100)->get();

            foreach ($coins as $key => $value) {
                $sum += floatval($value->bid_price);
                $count++;
            }

            $avg = $sum / $count;

            $val_percent = $avg - ($avg / 200);

            if ($price < $val_percent) {

                echo json_encode([
                    "current value" => $price,
                    "average value with less -0.5%" => $val_percent,
                    "status" => "Abaixo de -0.5%"
                ]);
            } else {

                echo json_encode([
                    "current value" => $price,
                    "average value with less -0.5%" => $val_percent,
                    "status" => "Acima de -0.5%"
                ]);
            }
        }
    }
}
