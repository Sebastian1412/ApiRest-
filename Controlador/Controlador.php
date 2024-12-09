<?php
header("Content-Type: application/json");
require 'Conexion.php';

function writeLog($message) {
    $logFile = __DIR__ . '/apirest.log';
    $timestamp = date('Y-m-d H:i:s');
    $entry = "[$timestamp] $message" . PHP_EOL;
    file_put_contents($logFile, $entry, FILE_APPEND);
}

$method = $_SERVER['REQUEST_METHOD'];
$requestUri = $_SERVER['REQUEST_URI'];
$scriptName = $_SERVER['SCRIPT_NAME'];

writeLog("Iniciando ejecución. Método: $method, URI: $requestUri");

$baseUri = str_replace($scriptName, '', $requestUri);
$path = explode('/', trim($baseUri, '/'));

writeLog("Ruta procesada: " . json_encode($path));

// Validar la ruta
if (isset($path[0]) && $path[0] === 'usuarios') {
    switch ($method) {
        case 'GET':
            if (isset($path[1])) {
                getUsuario($path[1]);
            } else {
                getUsuarios();
            }
            break;
        case 'POST':
            createUsuario();
            break;
        case 'PUT':
            if (isset($path[1])) {
                updateUsuario($path[1]);
            } else {
                echo json_encode(["error" => "ID de usuario requerido para actualizar."]);
            }
            break;
        case 'DELETE':
            if (isset($path[1])) {
                deleteUsuario($path[1]);
            } else {
                echo json_encode(["error" => "ID de usuario requerido para eliminar."]);
            }
            break;
        default:
            echo json_encode(["error" => "Método no soportado"]);
    }
} else {
    echo json_encode(["error" => "Ruta no válida"]);
}

function getUsuarios() {
    global $pdo;
    try {
        $stmt = $pdo->query("SELECT * FROM usuarios");
        writeLog("Consulta realizada correctamente.");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        writeLog("Operación 'GET' realizada en '/usuarios'.");
    } catch (Exception $e) {
        writeLog("Error al realizar la consulta: " . $e->getMessage());
        echo json_encode(["error" => "Error al realizar la consulta."]);
    }    
}

function getUsuario($id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($usuario) {
            echo json_encode($usuario);
            writeLog("Operación 'GET' realizada en '/usuarios/$id'.");
        } else {
            echo json_encode(["error" => "Usuario no encontrado"]);
            writeLog("Operación 'GET' realizada en '/usuarios/$id', pero el usuario no existe.");
        }
    } catch (Exception $e) {
        echo json_encode(["error" => "Error al obtener el usuario: " . $e->getMessage()]);
        writeLog("Error al obtener el usuario con ID $id: " . $e->getMessage());
    }
}

function createUsuario() {
    global $pdo;

    // Obtener y decodificar los datos del cuerpo de la solicitud
    $data = json_decode(file_get_contents('php://input'), true);

    // Validar los datos
    $errors = validateUserData($data);
    if (!empty($errors)) {
        echo json_encode(["error" => $errors]);
        return;
    }

    try {
        // Escapar los datos antes de insertar
        $escapedData = array_map('htmlspecialchars', $data);
        $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, rut, correo, fecha_nacimiento) VALUES (?, ?, ?, ?)");
        $stmt->execute([$escapedData['nombre'], $escapedData['rut'], $escapedData['correo'], $escapedData['fecha_nacimiento']]);
        echo json_encode(["message" => "Usuario creado", "id" => $pdo->lastInsertId()]);
        writeLog("Operación 'POST' realizada en '/usuarios' con ID " . $pdo->lastInsertId());
    } catch (Exception $e) {
        // Manejo de errores en la base de datos
        echo json_encode(["error" => "Error al crear el usuario."]);
        writeLog("Error al crear el usuario: " . $e->getMessage());
    }
}

function updateUsuario($id) {
    global $pdo;
    $data = json_decode(file_get_contents('php://input'), true);

    // Validar los datos, permitiendo campos opcionales
    $errors = validateUserData($data);
    if (!empty($errors)) {
        echo json_encode(["error" => $errors]);
        return;
    }

    try {
        // Escapar los datos antes de actualizar
        $escapedData = array_map('htmlspecialchars', $data);
        // Preparar la consulta de actualización
        $stmt = $pdo->prepare("UPDATE usuarios SET nombre = ?, rut = ?, correo = ?, fecha_nacimiento = ? WHERE id = ?");
        $stmt->execute([$escapedData['nombre'], $escapedData['rut'], $escapedData['correo'], $escapedData['fecha_nacimiento'], $id]);

        if ($stmt->rowCount() > 0) {
            echo json_encode(["message" => "Usuario actualizado"]);
        } else {
            echo json_encode(["error" => "No se encontró el usuario o no hubo cambios"]);
        }
    } catch (Exception $e) {
        // Manejo de errores en la base de datos
        echo json_encode(["error" => "Error al actualizar el usuario: " . $e->getMessage()]);
        writeLog("Error al actualizar usuario con ID $id: " . $e->getMessage());
    }
}

function deleteUsuario($id) {
    global $pdo;

    // Validar que el ID existe
    if (empty($id) || !is_numeric($id)) {
        echo json_encode(["error" => "ID de usuario inválido"]);
        return;
    }

    try {
        // Preparar la consulta de eliminación
        $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);

        if ($stmt->rowCount() > 0) {
            echo json_encode(["message" => "Usuario eliminado"]);
            writeLog("Operación 'DELETE' realizada en '/usuarios/$id'.");
        } else {
            echo json_encode(["error" => "No se encontró el usuario"]);
            writeLog("Operación 'DELETE' realizada en '/usuarios/$id', pero el usuario no existe.");
        }
    } catch (Exception $e) {
        // Manejo de errores en la base de datos
        echo json_encode(["error" => "Error al eliminar el usuario: " . $e->getMessage()]);
        writeLog("Error al eliminar usuario con ID $id: " . $e->getMessage());
    }
}

function validateUserData($data) {
    $errors = [];

    if (empty($data['nombre'])) {
        $errors[] = "El campo 'nombre' es obligatorio.";
    }

    if (empty($data['rut']) || !preg_match('/^\d{7,8}-[0-9Kk]$/', $data['rut'])) {
        $errors[] = "El campo 'rut' no tiene un formato válido.";
    }

    if (!empty($data['fecha_nacimiento']) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['fecha_nacimiento'])) {
        $errors[] = "El campo 'fecha_nacimiento' debe estar en el formato AAAA-MM-DD.";
    }

    return $errors;
}


?>
