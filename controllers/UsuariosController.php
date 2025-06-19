<?php

namespace Controllers;

use Exception;
use MVC\Router;
use Model\ActiveRecord;
use Model\Usuarios;

class UsuariosController extends ActiveRecord
{
    public static function renderizarPagina(Router $router)
    {
        // Verificar permisos (ajusta según tu sistema de permisos)
        // hasPermission(['ADMIN']);
        
        $router->render('usuarios/index', []);
    }

    public static function guardarAPI()
    {
        getHeadersApi();

        try {
            // Sanitización de primer nombre con validación
            $_POST['usuario_nom1'] = ucwords(strtolower(trim(htmlspecialchars($_POST['usuario_nom1']))));
            
            $cantidad_nombre = strlen($_POST['usuario_nom1']);
            
            if ($cantidad_nombre < 2) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El primer nombre debe tener más de 1 carácter'
                ]);
                return;
            }
            
            // Sanitización de primer apellido con validación
            $_POST['usuario_ape1'] = ucwords(strtolower(trim(htmlspecialchars($_POST['usuario_ape1']))));
            $cantidad_apellido = strlen($_POST['usuario_ape1']);
            
            if ($cantidad_apellido < 2) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El primer apellido debe tener más de 1 carácter'
                ]);
                return;
            }
            
            // Validación de teléfono como entero
            $_POST['usuario_tel'] = filter_var($_POST['usuario_tel'], FILTER_SANITIZE_NUMBER_INT);
            
            if (strlen((string)$_POST['usuario_tel']) != 8) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El teléfono debe tener exactamente 8 dígitos'
                ]);
                return;
            }
            
            // Sanitización de dirección
            $_POST['usuario_direc'] = ucwords(strtolower(trim(htmlspecialchars($_POST['usuario_direc']))));
            
            // Validación de DPI como string de 13 caracteres
            $_POST['usuario_dpi'] = filter_var($_POST['usuario_dpi'], FILTER_VALIDATE_INT);
            
            if (strlen($_POST['usuario_dpi']) != 13) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'La cantidad de digitos del DPI debe de ser igual a 13'
                ]);
                return;
            }
            
            // Sanitización y validación de correo        
            $_POST['usuario_correo'] = filter_var($_POST['usuario_correo'], FILTER_SANITIZE_EMAIL);

            if (!filter_var($_POST['usuario_correo'], FILTER_VALIDATE_EMAIL)) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El correo electronico ingresado es invalido'
                ]);
                return;
            }
            
            // Verificar si el correo ya existe
            $usuarioExistente = self::fetchFirst("SELECT usuario_id FROM jemg_usuarios WHERE usuario_correo = '{$_POST['usuario_correo']}'");
            if ($usuarioExistente) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El correo electrónico ya está registrado'
                ]);
                exit;
            }
            
            // Verificar si el DPI ya existe
            $dpiExistente = self::fetchFirst("SELECT usuario_id FROM jemg_usuarios WHERE usuario_dpi = '{$_POST['usuario_dpi']}'");
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
            
            // Generación de token único
            $_POST['usuario_token'] = uniqid();
            $dpi = $_POST['usuario_dpi'];
            
            $_POST['usuario_situacion'] = 1;
            
            // Manejo de fotografía si se envía
            if (isset($_FILES['usuario_fotografia']) && $_FILES['usuario_fotografia']['error'] === 0) {
                $file = $_FILES['usuario_fotografia'];
                $fileName = $file['name'];
                $fileTmpName = $file['tmp_name'];
                $fileSize = $file['size'];
                $fileError = $file['error'];
                
                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                
                // Extensiones permitidas
                $allowed = ['jpg', 'jpeg', 'png'];
                
                if (!in_array($fileExtension, $allowed)) {
                    http_response_code(400);
                    echo json_encode([
                        'codigo' => 2,
                        'mensaje' => 'Solo se permiten archivos JPG, PNG o JPEG',
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
                
                $ruta = "storage/fotosUsuarios/$dpi.$fileExtension";
                $subido = move_uploaded_file($file['tmp_name'], __DIR__ . "../../" . $ruta);
                
                if ($subido) {
                    $_POST['usuario_fotografia'] = $ruta;
                } else {
                    http_response_code(500);
                    echo json_encode([
                        'codigo' => 0,
                        'mensaje' => 'Error al subir la fotografía'
                    ]);
                    exit;
                }
            }
            
            // Hash de la contraseña
            $_POST['usuario_contra'] = password_hash($_POST['usuario_contra'], PASSWORD_DEFAULT);
            
            // Limpiar campos no necesarios
            unset($_POST['confirmar_contra']);
            
            $usuario = new Usuarios($_POST);
            $resultado = $usuario->crear();

            if($resultado['resultado'] == 1) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Usuario registrado correctamente',
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error al registrar el usuario',
                    'detalle' => $resultado
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

    public static function buscarUsuarios()
    {
        getHeadersApi();
        
        try {
            $sql = "SELECT * FROM jemg_usuarios WHERE usuario_situacion = 1";
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

    public static function eliminarUsuario()
    {
        getHeadersApi();
        
        try {
            $id = filter_var($_POST['usuario_id'], FILTER_SANITIZE_NUMBER_INT);
            $consulta = "UPDATE jemg_usuarios SET usuario_situacion = 0 WHERE usuario_id = $id";
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
                'mensaje' => 'Error al eliminar usuario',
                'detalle' => $e->getMessage()
            ]);
        }
    }
}