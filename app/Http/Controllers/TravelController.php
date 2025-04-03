<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class TravelController extends Controller
{
    private $cities = [
        'London' => ['currency' => 'GBP', 'symbol' => '£'],
        'New York' => ['currency' => 'USD', 'symbol' => '$'],
        'Paris' => ['currency' => 'EUR', 'symbol' => '€'],
        'Tokyo' => ['currency' => 'JPY', 'symbol' => '¥'],
        'Madrid' => ['currency' => 'EUR', 'symbol' => '€']
    ];

    public function showForm()
    {
        return view('travel.form', ['cities' => array_keys($this->cities)]);
    }

    public function calculate(Request $request)
    {
        $validated = $request->validate([
            'city' => 'required|in:' . implode(',', array_keys($this->cities)),
            'budget' => 'required|numeric|min:1'
        ]);

        $city = $request->city;
        $budget = $request->budget;
        $currency = $this->cities[$city]['currency'];
        $symbol = $this->cities[$city]['symbol'];

        // Obtener clima
        $weather = $this->getWeather($city);
        
        // Obtener tasa de cambio
        $exchange = $this->getExchangeRate('COP', $currency);
        $converted = $budget * $exchange['rate'];
        $rate = $exchange['rate'];

        return view('travel.results', compact('city', 'budget', 'currency', 'symbol', 'weather', 'converted', 'rate'));
    }

    private function getWeather($city)
    {
        $client = new Client();
        $response = $client->get("http://api.openweathermap.org/data/2.5/weather?q={$city}&units=metric&appid=".env('WEATHER_API_KEY'));
        return json_decode($response->getBody(), true);
    }

    private function getExchangeRate($from, $to)
    {
        $client = new Client();
        $response = $client->get("https://v6.exchangerate-api.com/v6/".env('EXCHANGE_API_KEY')."/pair/{$from}/{$to}");
        $data = json_decode($response->getBody(), true);
        
        return [
            'rate' => $data['conversion_rate'],
            'date' => $data['time_last_update_utc']
        ];
    }
}