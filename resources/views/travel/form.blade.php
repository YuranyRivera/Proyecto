@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white">
        <h2>Calculadora de Presupuesto de Viaje</h2>
    </div>
    <div class="card-body">
        <form action="{{ route('travel.calculate') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="city" class="form-label">Ciudad Destino</label>
                <select class="form-select" id="city" name="city" required>
                    <option value="">Seleccione una ciudad</option>
                    @foreach($cities as $city)
                        <option value="{{ $city }}">{{ $city }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="budget" class="form-label">Presupuesto en COP</label>
                <div class="input-group">
                    <span class="input-group-text">$</span>
                    <input type="number" class="form-control" id="budget" name="budget" required min="1">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Calcular</button>
        </form>
    </div>
</div>
@endsection