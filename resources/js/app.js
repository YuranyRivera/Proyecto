$(document).ready(function() {
    // Validación básica del formulario
    $('form').submit(function(e) {
        if ($('#city').val() === '') {
            alert('Por favor seleccione una ciudad');
            e.preventDefault();
        }
        
        if ($('#budget').val() <= 0) {
            alert('El presupuesto debe ser mayor a cero');
            e.preventDefault();
        }
    });
});