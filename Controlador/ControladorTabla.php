<?php
// Incluir la conexión a la base de datos
require 'Conexion.php';

// Configuración para habilitar las cabeceras adecuadas
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

try {
    // Preparar la consulta SQL para seleccionar todos los datos
    $stmt = $pdo->query("SELECT * FROM usuarios");

    // Verificar si hay resultados
    if ($stmt->rowCount() > 0) {
        // Obtener los resultados en un array asociativo
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Respuesta en formato JSON para DataTables
        echo json_encode([
            "data" => $usuarios // Clave esperada por DataTables
        ]);
    } else {
        // Si no hay datos, enviar una respuesta vacía
        echo json_encode([
            "data" => [] // DataTables requiere este formato
        ]);
    }
} catch (Exception $e) {
    // Enviar un mensaje de error en caso de problemas
    echo json_encode([
        "error" => "Error al obtener los datos: " . $e->getMessage()
    ]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'actualizar') {
    actualizarUsuario();
}

function actualizarUsuario()
{
    global $pdo;
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $rut = $_POST['rut'];
    $correo = $_POST['correo'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];

    try {
        $stmt = $pdo->prepare("UPDATE usuarios SET nombre = :nombre, rut = :rut, correo = :correo, fecha_nacimiento = :fecha_nacimiento WHERE id = :id");
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':rut', $rut);
        $stmt->bindParam(':correo', $correo);
        $stmt->bindParam(':fecha_nacimiento', $fecha_nacimiento);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        echo json_encode(["mensaje" => "Usuario actualizado correctamente."]);
    } catch (Exception $e) {
        echo json_encode(["error" => "Error al actualizar el usuario: " . $e->getMessage()]);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    if ($input['accion'] === 'eliminar') {
        $id = $input['id'];

        try {
            $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                echo json_encode(['mensaje' => 'Registro eliminado correctamente.']);
            } else {
                echo json_encode(['error' => 'No se pudo eliminar el registro.']);
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Error en la base de datos: ' . $e->getMessage()]);
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';

    if ($accion === 'insertar') {
        $nombre = $_POST['nombre'];
        $rut = $_POST['rut'];
        $correo = $_POST['correo'];
        $fechaNacimiento = $_POST['fecha_nacimiento'];

        try {
            $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, rut, correo, fecha_nacimiento) VALUES (:nombre, :rut, :correo, :fecha_nacimiento)");
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':rut', $rut);
            $stmt->bindParam(':correo', $correo);
            $stmt->bindParam(':fecha_nacimiento', $fechaNacimiento);

            if ($stmt->execute()) {
                echo json_encode(['mensaje' => 'Registro agregado correctamente.']);
            } else {
                echo json_encode(['error' => 'No se pudo agregar el registro.']);
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Error en la base de datos: ' . $e->getMessage()]);
        }
    }
}
