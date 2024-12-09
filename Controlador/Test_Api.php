<?php

// Función para realizar y mostrar los resultados de una solicitud GET
function testGet($endpoint) {
    $response = file_get_contents($endpoint);
    $data = json_decode($response, true);
    var_dump($data);
}

// Función para realizar y mostrar los resultados de una solicitud POST
function testPost($endpoint, $data) {
    $data = json_encode($data);
    $options = array(
        'http' => array(
            'header' => "Content-Type: application/json\r\n",
            'method' => 'POST',
            'content' => $data,
        ),
    );
    $context = stream_context_create($options);
    $response = file_get_contents($endpoint, false, $context);
    $result = json_decode($response, true);
    var_dump($result);
}

// Función para realizar y mostrar los resultados de una solicitud PUT
function testPut($endpoint, $data) {
    $data = json_encode($data);
    $options = array(
        'http' => array(
            'header' => "Content-Type: application/json\r\n",
            'method' => 'PUT',
            'content' => $data,
        ),
    );
    $context = stream_context_create($options);
    $response = file_get_contents($endpoint, false, $context);
    $result = json_decode($response, true);
    var_dump($result);
}

// Función para realizar y mostrar los resultados de una solicitud DELETE
function testDelete($endpoint) {
    $options = array(
        'http' => array(
            'method' => 'DELETE',
        ),
    );
    $context = stream_context_create($options);
    $response = file_get_contents($endpoint, false, $context);
    $status = http_response_code();
    var_dump($status);
}

// Pruebas

echo "Obtener todos los usuarios:\n";
testGet('http://localhost/Proyectos/Portafolio/ApiRestful/Controlador/Controlador.php/usuarios');
echo "\n";

echo "Obtener un usuario específico:\n";
testGet('http://localhost/Proyectos/Portafolio/ApiRestful/Controlador/Controlador.php/usuarios/1');
echo "\n";

echo "Intentar obtener un usuario que no existe:\n";
testGet('http://localhost/Proyectos/Portafolio/ApiRestful/Controlador/Controlador.php/usuarios/999');
echo "\n";

echo "Crear un nuevo usuario:\n";
testPost('http://localhost/Proyectos/Portafolio/ApiRestful/Controlador/Controlador.php/usuarios', array(
    "nombre" => "Nuevo Usuario",
    "correo" => "usuario@example.com"
));
echo "\n";

echo "Actualizar un usuario:\n";
testPut('http://localhost/Proyectos/Portafolio/ApiRestful/Controlador/Controlador.php/usuarios/1', array(
    "nombre" => "Usuario Actualizado",
    "correo" => "usuario_actualizado@example.com"
));
echo "\n";

echo "Eliminar un usuario:\n";
testDelete('http://localhost/Proyectos/Portafolio/ApiRestful/Controlador/Controlador.php/usuarios/1');
echo "\n";

?>
