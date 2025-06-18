<?php

namespace Controllers;

use Exception;
use MVC\Router;
use Model\ActiveRecord;
use Model\Usuarios;

class RegistroController extends ActiveRecord
{

    public static function renderizarPagina(Router $router)
    {
        $router->render('usuarios/index', []);
    }


    public static function guardarAPI()
    {
        getHeadersApi();
        
        // Validar que todos los campos obligatorios estén presentes
        $camposObligatorios = ['usuario_nom1', 'usuario_nom2', 'usuario_ape1', 'usuario_ape2', 'usuario_tel', 'usuario_direc', 'usuario_dpi', 'usuario_correo', 'usuario_contra', 'confirmar_contra'];
        
        foreach ($camposObligatorios as $campo) {
            if (empty($_POST[$campo])) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => "El campo {$campo} es obligatorio"
                ]);
                exit;
            }
        }
        
        // Sanitización y validación de primer nombre
        $_POST['usuario_nom1'] = ucwords(strtolower(trim(htmlspecialchars($_POST['usuario_nom1']))));
        if (strlen($_POST['usuario_nom1']) < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Primer nombre debe tener más de 1 caracter'
            ]);
            exit;
        }
        
        // Sanitización y validación de segundo nombre
        $_POST['usuario_nom2'] = ucwords(strtolower(trim(htmlspecialchars($_POST['usuario_nom2']))));
        if (strlen($_POST['usuario_nom2']) < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Segundo nombre debe tener más de 1 caracter'
            ]);
            exit;
        }
        
        // Sanitización y validación de primer apellido
        $_POST['usuario_ape1'] = ucwords(strtolower(trim(htmlspecialchars($_POST['usuario_ape1']))));
        if (strlen($_POST['usuario_ape1']) < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Primer apellido debe tener más de 1 caracter'
            ]);
            exit;
        }
        
        // Sanitización y validación de segundo apellido
        $_POST['usuario_ape2'] = ucwords(strtolower(trim(htmlspecialchars($_POST['usuario_ape2']))));
        if (strlen($_POST['usuario_ape2']) < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Segundo apellido debe tener más de 1 caracter'
            ]);
            exit;
        }
        
        // Validación de teléfono
        $_POST['usuario_tel'] = filter_var($_POST['usuario_tel'], FILTER_SANITIZE_NUMBER_INT);
        if (strlen($_POST['usuario_tel']) != 8) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El teléfono debe tener 8 números'
            ]);
            exit;
        }
        
        // Sanitización de dirección
        $_POST['usuario_direc'] = ucwords(strtolower(trim(htmlspecialchars($_POST['usuario_direc']))));
        if (strlen($_POST['usuario_direc']) < 5) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La dirección debe tener al menos 5 caracteres'
            ]);
            exit;
        }
        
        // Validación de DPI
        $_POST['usuario_dpi'] = filter_var($_POST['usuario_dpi'], FILTER_VALIDATE_INT);
        if (strlen($_POST['usuario_dpi']) != 13) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El DPI debe tener exactamente 13 dígitos'
            ]);
            exit;
        }
        
        // Validación de correo
        $_POST['usuario_correo'] = filter_var($_POST['usuario_correo'], FILTER_SANITIZE_EMAIL);
        if (!filter_var($_POST['usuario_correo'], FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El correo electrónico no es válido'
            ]);
            exit;
        }
        
        // Verificar si el correo ya existe
        $usuarioExistente = self::fetchFirst("SELECT usuario_id FROM jemg_usuario WHERE usuario_correo = '{$_POST['usuario_correo']}'");
        if ($usuarioExistente) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El correo electrónico ya está registrado'
            ]);
            exit;
        }
        
        // Verificar si el DPI ya existe
        $dpiExistente = self::fetchFirst("SELECT usuario_id FROM jemg_usuario WHERE usuario_dpi = '{$_POST['usuario_dpi']}'");
        if ($dpiExistente) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El DPI ya está registrado'
            ]);
            exit;
        }
        
        // Validación de contraseña
        if (strlen($_POST['usuario_contra']) < 8) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La contraseña debe tener al menos 8 caracteres'
            ]);
            exit;
        }
        
        // Validación de confirmación de contraseña
        if ($_POST['usuario_contra'] !== $_POST['confirmar_contra']) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Las contraseñas no coinciden'
            ]);
            exit;
        }
        
        // Generar token y asignar fechas
        $_POST['usuario_token'] = uniqid();
        $_POST['usuario_fecha_creacion'] = date('Y-m-d');
        
        // Si no se especifica fecha de contratación, usar la fecha actual
        if (empty($_POST['usuario_fecha_contra'])) {
            $_POST['usuario_fecha_contra'] = date('Y-m-d');
        }
        
        // Hash de la contraseña
        $_POST['usuario_contra'] = password_hash($_POST['usuario_contra'], PASSWORD_DEFAULT);
        
        // Manejo de fotografía
        $rutaFoto = null;
        if (isset($_FILES['usuario_fotografia']) && $_FILES['usuario_fotografia']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['usuario_fotografia'];
            $fileName = $file['name'];
            $fileSize = $file['size'];
            $fileError = $file['error'];
            
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            
            if (!in_array($fileExtension, $allowed)) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 2,
                    'mensaje' => 'Solo puede cargar archivos JPG, PNG, GIF o JPEG',
                ]);
                exit;
            }
            
            if ($fileSize >= 2000000) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 2,
                    'mensaje' => 'La imagen debe pesar menos de 2MB',
                ]);
                exit;
            }
            
            $dpi = $_POST['usuario_dpi'];
            $rutaFoto = "storage/fotosUsuarios/$dpi.$fileExtension";
            
            if (!move_uploaded_file($file['tmp_name'], __DIR__ . "../../" . $rutaFoto)) {
                http_response_code(500);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error al subir la fotografía',
                ]);
                exit;
            }
        }
        
        try {
            $usuario = new Usuarios($_POST);
            $usuario->usuario_fotografia = $rutaFoto;
            $resultado = $usuario->crear();

            if ($resultado['resultado'] == 1) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Usuario registrado correctamente',
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error al registrar al usuario',
                ]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error en el servidor: ' . $e->getMessage(),
            ]);
        }
    }

    public static function buscarUsuarios()
    {
        getHeadersApi();
        
        try {
            $sql = "SELECT * FROM jemg_usuario WHERE usuario_situacion = 1 ORDER BY usuario_id DESC";
            $data = self::fetchArray($sql);

            if (count($data) > 0) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Usuarios obtenidos correctamente',
                    'data' => $data
                ]);
            } else {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'No hay usuarios registrados',
                    'data' => []
                ]);
            }

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error en el servidor',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function modificarUsuario()
    {
        getHeadersApi();

        if (empty($_POST['usuario_id'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'ID de usuario requerido'
            ]);
            return;
        }

        $id = filter_var($_POST['usuario_id'], FILTER_SANITIZE_NUMBER_INT);

        // Verificar que el usuario existe
        $usuarioExistente = Usuarios::find($id);
        if (!$usuarioExistente) {
            http_response_code(404);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Usuario no encontrado'
            ]);
            return;
        }

        // Validar campos obligatorios
        $camposObligatorios = ['usuario_nom1', 'usuario_nom2', 'usuario_ape1', 'usuario_ape2', 'usuario_tel', 'usuario_direc', 'usuario_dpi', 'usuario_correo'];
        
        foreach ($camposObligatorios as $campo) {
            if (empty($_POST[$campo])) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => "El campo {$campo} es obligatorio"
                ]);
                return;
            }
        }

        // Sanitización y validación (igual que en guardar pero sin contraseñas)
        $_POST['usuario_nom1'] = ucwords(strtolower(trim(htmlspecialchars($_POST['usuario_nom1']))));
        if (strlen($_POST['usuario_nom1']) < 2) {
            http_response_code(400);
            echo json_encode(['codigo' => 0, 'mensaje' => 'Primer nombre debe tener más de 1 caracter']);
            return;
        }

        $_POST['usuario_nom2'] = ucwords(strtolower(trim(htmlspecialchars($_POST['usuario_nom2']))));
        if (strlen($_POST['usuario_nom2']) < 2) {
            http_response_code(400);
            echo json_encode(['codigo' => 0, 'mensaje' => 'Segundo nombre debe tener más de 1 caracter']);
            return;
        }

        $_POST['usuario_ape1'] = ucwords(strtolower(trim(htmlspecialchars($_POST['usuario_ape1']))));
        if (strlen($_POST['usuario_ape1']) < 2) {
            http_response_code(400);
            echo json_encode(['codigo' => 0, 'mensaje' => 'Primer apellido debe tener más de 1 caracter']);
            return;
        }

        $_POST['usuario_ape2'] = ucwords(strtolower(trim(htmlspecialchars($_POST['usuario_ape2']))));
        if (strlen($_POST['usuario_ape2']) < 2) {
            http_response_code(400);
            echo json_encode(['codigo' => 0, 'mensaje' => 'Segundo apellido debe tener más de 1 caracter']);
            return;
        }

        $_POST['usuario_tel'] = filter_var($_POST['usuario_tel'], FILTER_SANITIZE_NUMBER_INT);
        if (strlen($_POST['usuario_tel']) != 8) {
            http_response_code(400);
            echo json_encode(['codigo' => 0, 'mensaje' => 'El teléfono debe tener 8 números']);
            return;
        }

        $_POST['usuario_direc'] = ucwords(strtolower(trim(htmlspecialchars($_POST['usuario_direc']))));
        if (strlen($_POST['usuario_direc']) < 5) {
            http_response_code(400);
            echo json_encode(['codigo' => 0, 'mensaje' => 'La dirección debe tener al menos 5 caracteres']);
            return;
        }

        $_POST['usuario_dpi'] = filter_var($_POST['usuario_dpi'], FILTER_SANITIZE_NUMBER_INT);
        if (strlen($_POST['usuario_dpi']) != 13) {
            http_response_code(400);
            echo json_encode(['codigo' => 0, 'mensaje' => 'El DPI debe tener exactamente 13 dígitos']);
            return;
        }

        $_POST['usuario_correo'] = filter_var($_POST['usuario_correo'], FILTER_SANITIZE_EMAIL);
        if (!filter_var($_POST['usuario_correo'], FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode(['codigo' => 0, 'mensaje' => 'El correo electrónico no es válido']);
            return;
        }

        // Verificar duplicados (excluyendo el usuario actual)
        $usuarioCorreoExistente = self::fetchFirst("SELECT usuario_id FROM jemg_usuario WHERE usuario_correo = '{$_POST['usuario_correo']}' AND usuario_id != $id");
        if ($usuarioCorreoExistente) {
            http_response_code(400);
            echo json_encode(['codigo' => 0, 'mensaje' => 'El correo electrónico ya está registrado por otro usuario']);
            return;
        }

        $dpiExistente = self::fetchFirst("SELECT usuario_id FROM jemg_usuario WHERE usuario_dpi = '{$_POST['usuario_dpi']}' AND usuario_id != $id");
        if ($dpiExistente) {
            http_response_code(400);
            echo json_encode(['codigo' => 0, 'mensaje' => 'El DPI ya está registrado por otro usuario']);
            return;
        }

        // Validar fecha de contratación
        $fechaContra = !empty($_POST['usuario_fecha_contra']) ? $_POST['usuario_fecha_contra'] : $usuarioExistente->usuario_fecha_contra;
        
        // Validar situación
        $situacion = isset($_POST['usuario_situacion']) ? (int)$_POST['usuario_situacion'] : 1;
        if (!in_array($situacion, [0, 1])) {
            $situacion = 1;
        }

        try {
            $usuarioExistente->sincronizar([
                'usuario_nom1' => $_POST['usuario_nom1'],
                'usuario_nom2' => $_POST['usuario_nom2'],
                'usuario_ape1' => $_POST['usuario_ape1'],
                'usuario_ape2' => $_POST['usuario_ape2'],
                'usuario_tel' => $_POST['usuario_tel'],
                'usuario_direc' => $_POST['usuario_direc'],
                'usuario_dpi' => $_POST['usuario_dpi'],
                'usuario_correo' => $_POST['usuario_correo'],
                'usuario_fecha_contra' => $fechaContra,
                'usuario_situacion' => $situacion
            ]);
            
            $resultado = $usuarioExistente->actualizar();
            
            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Usuario modificado exitosamente'
            ]);
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al modificar usuario: ' . $e->getMessage()
            ]);
        }
    }

    public static function eliminarUsuario()
    {
        getHeadersApi();
        
        if (empty($_POST['usuario_id'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'ID de usuario requerido'
            ]);
            return;
        }
        
        try {
            $id = filter_var($_POST['usuario_id'], FILTER_SANITIZE_NUMBER_INT);
            $consulta = "UPDATE jemg_usuario SET usuario_situacion = 0 WHERE usuario_id = $id";
            self::SQL($consulta);
            
            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Usuario eliminado exitosamente'
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al eliminar usuario: ' . $e->getMessage()
            ]);
        }
    }
    
}