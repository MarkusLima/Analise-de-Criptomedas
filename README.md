## Project Info
Start of the project on 23/05/2022

## Project objective
Check crioptomedas values

## Technologies used
- Json
- Rest
- API Binance
- Artisan comand
- Mysql
- Laravel 8


## Create project
composer create-project laravel/laravel:^8.0 currency_analysis

## Create Artisan Comand
- php artisan make:command SaveBidPrice
- php artisan make:command SaveBidPriceForSymbol
- php artisan make:command CheckAvgBigPrice

## Create Migration
php artisan make:migration create_coins_table

## Create Models
php artisan make:model Coin

## Run this project

- 1.configure your database
- 2.create the database( create database currency_analysis )
- 3.renamed .env.example to .env
- 4.configure the database connection in the .env file
- 5.composer install
- 6.php artisan migrate


### run
php artisan c:saveBidPriceOnDataBase
Este comando salva todas as moedas disponiveis na API Binance https://api1.binance.com/api/v1/ticker/price

``Caso não for informado o {symbol} irá perguntar se deseja prosseguir
Missed the symbol parameter, want to continue? (yes/no) [no]:
> yes

No caso (yes) irá salvar todas e mostrar a seguinte menssagem
All data has been saved.
array:2 [
  "msg" => "save to database!"
  "success" => true
]
``

php artisan c:saveBidPriceOnDataBase params
Este comando salva somente a moeda informada na API Binance https://api1.binance.com/api/v1/ticker/bookTicker?symbol={symbol}
OBS: o param{symbol} é a nomeclatura da moeda (exemplo: "btcusdt")

``Caso o {symbol} esteja correto e a API Binance retornar os dados corretos aparecerá esta menssagem
Data has been saved.
array:2 [
  "msg" => "save to database!"
  "success" => true
]
Caso tenha alguma informação incorreta
Data has been saved.
array:2 [
  "msg" => "data not found!"
  "success" => false
]
``

Este comando ativa o websocket client na API Binance tls://stream.binance.com:9443/ws/aggTrade
php artisan c:saveBidPriceOnDataBase params
OBS: O params é um {symbol}(exemplo: "btcusdt") válido que esteja previamente salvo no banco para que haja comparação de valores
``
Caso não esteja abaixo de 0.5%
{"current value":28979.42,"average value with less -0.5%":28809.12386666666,"status":"Acima de -0.5%"}

Caso esteja abaixo de 0.5%
{"current value":28979.42,"average value with less -0.5%":28809.12386666666,"status":"Abaixo de -0.5%"}
``


