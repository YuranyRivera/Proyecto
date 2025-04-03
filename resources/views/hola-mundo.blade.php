<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hola Mundo</title>
</head>
<body>
    <h1>¡Hola Mundo desde Laravel!</h1>
    
    <!-- Ejemplo con jQuery -->
    <button id="saludo-btn">Saludar</button>
    
    <!-- Incluir jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Nuestro script -->
    <script>
    $(document).ready(function() {
        $('#saludo-btn').click(function() {
            alert('¡Hola desde jQuery!');
        });
    });
    </script>
</body>
</html>