
$(document).ready(function() {
    // Configuración global de AJAX
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        timeout: 15000, // Incrementar timeout a 15 segundos
        error: function(xhr, status, error) {
            console.error("AJAX Error:", status, error);
            
            // Mostrar mensaje de error más específico
            let errorMessage = 'Error de conexión';
            if (xhr.status === 404) {
                errorMessage = 'Recurso no encontrado';
            } else if (xhr.status === 500) {
                errorMessage = 'Error interno del servidor';
            }
            
            // Usar una notificación menos intrusiva
            $('#error-message').text(errorMessage).show().fadeOut(5000);
            $('button[type="submit"]').html('Calcular');
        }
    });

    // Añadir elemento para mensajes de error
    $('body').append('<div id="error-message" style="display:none; color:red; text-align:center;"></div>');

    // Cargar ciudades al iniciar
    loadCities();
    
    // Manejar envío del formulario
    $('#travel-form').submit(function(e) {
        e.preventDefault();
        calculateTravelData();
    });
    
    // Manejar botón volver
    $('#back-btn').click(function() {
        $('#form-section').show();
        $('#results-section').hide();
        $('#budget').val('');
    });
});

function loadCities() {
    $.ajax({
        url: '/api/cities', // Ruta de Laravel
        method: 'GET',
        beforeSend: function() {
            $('#city').prop('disabled', true)
                      .html('<option value="" disabled selected>Cargando ciudades...</option>');
        },
        success: function(response) {
            let options = '<option value="" disabled selected>Selecciona una ciudad</option>';
            
            // Manejar diferentes formatos de respuesta de Laravel
            if (response.data) {
                response.data.forEach(city => {
                    options += `<option value="${city.id}">${city.name}</option>`;
                });
            } else if (Array.isArray(response)) {
                response.forEach(city => {
                    options += `<option value="${city.id}">${city.name}</option>`;
                });
            }
            
            $('#city').html(options).prop('disabled', false);
        },
        error: function() {
            $('#city').html('<option value="" disabled selected>Error cargando ciudades</option>')
                      .prop('disabled', false);
        }
    });
}

function calculateTravelData() {
    const city = $('#city').val();
    const budget = $('#budget').val();
    
    if (!city || !budget) {
        $('#error-message').text('Por favor selecciona una ciudad y presupuesto').show().fadeOut(3000);
        return;
    }
    
    $('button[type="submit"]')
        .html('Calculando... <span class="loading">⏳</span>')
        .prop('disabled', true);
    
    $.ajax({
        url: '/api/travel-data', // Ruta de Laravel
        method: 'POST',
        data: {
            city_id: city,
            budget: budget
        },
        success: function(response) {
            displayResults(response.data);
            $('button[type="submit"]')
                .html('Calcular')
                .prop('disabled', false);
        },
        error: function(xhr) {
            $('button[type="submit"]')
                .html('Calcular')
                .prop('disabled', false);
            
            // Mostrar error específico
            const errorMsg = xhr.responseJSON?.message || 'Error al calcular datos';
            $('#error-message').text(errorMsg).show().fadeOut(5000);
        }
    });
}

function displayResults(data) {
    // Verificar si los datos están completos
    if (!data || !data.weather || !data.currency || !data.budget) {
        $('#error-message').text('Datos incompletos').show().fadeOut(3000);
        return;
    }

    // Mostrar sección de resultados
    $('#form-section').hide();
    $('#results-section').show();
    
    // Mostrar información del clima
    const weatherHtml = `
        <div class="weather-card">
            <h4>${data.weather.city || 'Ciudad'}</h4>
            <div class="weather-icon">${getWeatherIcon(data.weather.description || 'clear')}</div>
            <div class="temperature">${data.weather.temperature || 'N/A'} °C</div>
            <p>${capitalizeFirstLetter(data.weather.description || 'Despejado')}</p>
        </div>
    `;
    $('#weather-display').html(weatherHtml);
    
    // Mostrar información monetaria
    const currencyHtml = `
        <div class="currency-card">
            <p><strong>Moneda local:</strong> ${data.currency.code || 'N/A'} (${data.currency.symbol || '$'})</p>
            <p><strong>Presupuesto convertido:</strong> ${data.currency.symbol || '$'}${data.budget.converted || 'N/A'}</p>
            <p><strong>Tasa de cambio:</strong> 1 COP = ${data.budget.rate || 'N/A'} ${data.currency.code || ''}</p>
            <p><small>Última actualización: ${formatDate(data.budget.last_updated)}</small></p>
        </div>
    `;
    $('#currency-display').html(currencyHtml);
}

// Resto de funciones auxiliares permanecen igual
function getWeatherIcon(description) {
    const icons = {
        'clear': '☀️',
        'cloud': '☁️',
        'rain': '🌧️',
        'snow': '❄️',
        'thunderstorm': '⛈️',
        'drizzle': '🌦️',
        'mist': '🌫️'
    };
    
    description = description.toLowerCase();
    
    for (const [key, icon] of Object.entries(icons)) {
        if (description.includes(key)) return icon;
    }
    
    return '🌈';
}

function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function formatDate(dateString) {
    if (!dateString) return 'Desconocido';
    try {
        const date = new Date(dateString);
        return date.toLocaleDateString('es-ES', { 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    } catch {
        return 'Fecha inválida';
    }
}