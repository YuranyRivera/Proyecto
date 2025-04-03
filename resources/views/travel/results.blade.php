@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header bg-success text-white">
        <h2>Resultados para {{ $city }}</h2>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-header bg-info text-white">
                        Información del Clima
                    </div>
                    <div class="card-body">
                        <p><strong>Temperatura actual:</strong> {{ $weather['main']['temp'] }}°C</p>
                        <p><strong>Condición:</strong> {{ $weather['weather'][0]['description'] }}</p>
                        <p><strong>Humedad:</strong> {{ $weather['main']['humidity'] }}%</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-header bg-info text-white">
                        Información Monetaria
                    </div>
                    <div class="card-body">
                        <p><strong>Moneda local:</strong> {{ $currency }} ({{ $symbol }})</p>
                        <p><strong>Presupuesto convertido:</strong> {{ $symbol }} {{ number_format($converted, 2) }}</p>
                        <p><strong>Tasa de cambio:</strong> 1 COP = {{ $symbol }} {{ number_format($rate, 6) }}</p>
                        <p><strong>Presupuesto original:</strong> ${{ number_format($budget, 2) }} COP</p>
                    </div>
                </div>
            </div>
        </div>
        <a href="{{ route('travel.form') }}" class="btn btn-primary">Volver</a>
    </div>
</div>
@endsection