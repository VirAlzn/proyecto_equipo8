<?php
header('Content-Type: application/json; charset=utf-8');
include 'conexion.php';

$action = $_GET['action'] ?? '';

switch ($action) {

    // === LISTAR USUARIOS ===
    case 'listar':
        $sql = "SELECT id_usuario, username, nombre_completo, rol, estado, fecha_registro FROM usuarios ORDER BY id_usuario DESC";
        $res = $conn->query($sql);
        $usuarios = [];
        while ($fila = $res->fetch_assoc()) {
            $usuarios[] = $fila;
        }
        echo json_encode($usuarios);
        break;

    // === BUSCAR USUARIO ===
    case 'buscar':
        $busqueda = $conn->real_escape_string($_GET['q'] ?? '');
        $sql = "SELECT id_usuario, username, nombre_completo, rol, estado FROM usuarios
                WHERE username LIKE '%$busqueda%' OR nombre_completo LIKE '%$busqueda%'";
        $res = $conn->query($sql);
        $usuarios = [];
        while ($fila = $res->fetch_assoc()) {
            $usuarios[] = $fila;
        }
        echo json_encode($usuarios);
        break;

    // === AGREGAR USUARIO ===
    case 'agregar':
        $nombre = $conn->real_escape_string($_POST['nombre']);
        $usuario = $conn->real_escape_string($_POST['usuario']);
        $contrasena = $conn->real_escape_string($_POST['contrasena']);
        $permiso = $conn->real_escape_string($_POST['permiso']);
        $estado = $conn->real_escape_string($_POST['estado']);

        if (!in_array($permiso, ['Administrador', 'Empleado'])) {
            echo json_encode(['success' => false, 'msg' => 'Rol inválido']);
            break;
        }

        $check = $conn->query("SELECT id_usuario FROM usuarios WHERE username='$usuario'");
        if ($check->num_rows > 0) {
            echo json_encode(['success' => false, 'msg' => 'El usuario ya existe.']);
            break;
        }

        $sql = "INSERT INTO usuarios (username, password, nombre_completo, rol, estado)
                VALUES ('$usuario', '$contrasena', '$nombre', '$permiso', '$estado')";
        echo json_encode(['success' => $conn->query($sql)]);
        break;

    // === ELIMINAR USUARIO ===
    case 'eliminar':
        $id = intval($_POST['id']);
        $sql = "DELETE FROM usuarios WHERE id_usuario=$id";
        echo json_encode(['success' => $conn->query($sql)]);
        break;

    // === ACTUALIZAR USUARIO ===
    case 'actualizar':
        $id = intval($_POST['id']);
        $nombre = $conn->real_escape_string($_POST['nombre']);
        $usuario = $conn->real_escape_string($_POST['usuario']);
        $permiso = $conn->real_escape_string($_POST['permiso']);
        $estado = $conn->real_escape_string($_POST['estado']);

        if (!in_array($permiso, ['Administrador', 'Empleado'])) {
            echo json_encode(['success' => false, 'msg' => 'Rol inválido']);
            break;
        }

        $sql = "UPDATE usuarios SET 
                    username='$usuario',
                    nombre_completo='$nombre',
                    rol='$permiso',
                    estado='$estado'
                WHERE id_usuario=$id";
        echo json_encode(['success' => $conn->query($sql)]);
        break;

    default:
        echo json_encode(['error' => 'Acción no válida']);
}

$conn->close();
?>
