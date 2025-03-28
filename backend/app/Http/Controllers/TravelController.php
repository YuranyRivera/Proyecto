<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TravelController extends Controller
{
    private $cities = [
        'London' => ['currency' => 'GBP', 'symbol' => '£'],
        'New York' => ['currency' => 'USD', 'symbol' => '$'],
        'Paris' => ['currency' => 'EUR', 'symbol' => '€'],
        'Tokyo' => ['currency' => 'JPY', 'symbol' => '¥'],
        'Madrid' => ['currency' => 'EUR', 'symbol' => '€']
    ];

    public function getCityData(Request $request)
    {
        $request->validate([
            'city' => 'required|in:London,New York,Paris,Tokyo,Madrid',
            'budget' => 'required|numeric|min:0'
        ]);

        $city = $request->input('city');
        $budget = $request->input('budget');
        
        // Obtener datos del clima
        $weather = $this->getWeatherData($city);
        
        // Obtener tasa de cambio
        $exchange = $this->getExchangeRate('COP', $this->cities[$city]['currency']);
        
        // Calcular presupuesto en moneda local
        $localBudget = $budget * $exchange['rate'];
        
        return response()->json([
            'weather' => [
                'city' => $city,
                'temperature' => $weather['temp'],
                'description' => $weather['description']
            ],
            'currency' => [
                'code' => $this->cities[$city]['currency'],
                'symbol' => $this->cities[$city]['symbol']
            ],
            'budget' => [
                'original' => $budget,
                'converted' => round($localBudget, 2),
                'rate' => $exchange['rate'],
                'last_updated' => $exchange['last_updated']
            ]
        ]);
    }

    private function getWeatherData($city)
    {
        $response = Http::get("https://api.openweathermap.org/data/2.5/weather", [
            'q' => $city,
            'units' => 'metric',
            'appid' => env('OPENWEATHERMAP_API_KEY')
        ]);

        $data = $response->json();
        
        return [
            'temp' => $data['main']['temp'],
            'description' => $data['weather'][0]['description']
        ];
    }
    public function getCities()
{
    $cities = [
        'London' => 'Londres',
        'New York' => 'New York',
        'Paris' => 'París',
        'Tokyo' => 'Tokio',
        'Madrid' => 'Madrid'
    ];
    
    return response()->json($cities);
}


    private function getExchangeRate($from, $to)
    {
        $response = Http::get("https://v6.exchangerate-api.com/v6/".env('EXCHANGERATE_API_KEY')."/pair/{$from}/{$to}");
        
        $data = $response->json();
        
        return [
            'rate' => $data['conversion_rate'],
            'last_updated' => $data['time_last_update_utc']
        ];
    }
}