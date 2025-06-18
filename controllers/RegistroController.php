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
        
        // Manejo de fotografía
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
        
        if ($fileError === 0) {
            $ruta = "storage/fotosUsuarios/$dpi.$fileExtension";
            $subido = move_uploaded_file($file['tmp_name'], __DIR__ . "../../" . $ruta);
            
            if ($subido) {
                // Hash de la contraseña
                $_POST['usuario_contra'] = password_hash($_POST['usuario_contra'], PASSWORD_DEFAULT);
                
            
                $_POST['usuario_fotografia'] = $ruta;
                
                
                unset($_POST['usuario_nom2']);
                unset($_POST['usuario_ape2']);
                unset($_POST['confirmar_contra']);
                unset($_POST['usuario_fecha_creacion']);
                unset($_POST['usuario_fecha_contra']);
                
                $usuario = new Usuarios($_POST);
                $resultado = $usuario->crear();

                if($resultado['resultado'] == 1) {
                    http_response_code(200);
                    echo json_encode([
                        'codigo' => 1,
                        'mensaje' => 'Usuario registrado correctamente',
                    ]);
                    exit;
                } else {
                    http_response_code(500);
                    echo json_encode([
                        'codigo' => 0,
                        'mensaje' => 'Error al registrar el usuario',
                        'detalle' => $resultado
                    ]);
                    exit;
                }
            } else {
                http_response_code(500);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error al subir la fotografía'
                ]);
                exit;
            }
        } else {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error en la carga de fotografía'
            ]);
            exit;
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

    

    // Función adicional para modificación parcial (PATCH)
    public static function modificarUsuarioParcial()
    {
        getHeadersApi();
        
        if (!isset($_POST['usuario_id']) || empty($_POST['usuario_id'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El ID del usuario es requerido'
            ]);
            return;
        }

        $id = (int)$_POST['usuario_id'];
        
        // Verificar que el usuario existe
        $usuarioExiste = self::fetchFirst("SELECT usuario_id FROM jemg_usuarios WHERE usuario_id = $id AND usuario_situacion = 1");
        if (!$usuarioExiste) {
            http_response_code(404);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Usuario no encontrado o inactivo'
            ]);
            return;
        }

        try {
            $campos_actualizar = [];
            $validaciones_ok = true;
            $errores = [];

            // Validar y preparar solo los campos enviados
            if (isset($_POST['usuario_nom1']) && !empty($_POST['usuario_nom1'])) {
                $nom1 = ucwords(strtolower(trim(htmlspecialchars($_POST['usuario_nom1']))));
                if (strlen($nom1) >= 2) {
                    $campos_actualizar[] = "usuario_nom1 = '$nom1'";
                } else {
                    $errores[] = 'El primer nombre debe tener más de 1 carácter';
                    $validaciones_ok = false;
                }
            }

            if (isset($_POST['usuario_ape1']) && !empty($_POST['usuario_ape1'])) {
                $ape1 = ucwords(strtolower(trim(htmlspecialchars($_POST['usuario_ape1']))));
                if (strlen($ape1) >= 2) {
                    $campos_actualizar[] = "usuario_ape1 = '$ape1'";
                } else {
                    $errores[] = 'El primer apellido debe tener más de 1 carácter';
                    $validaciones_ok = false;
                }
            }

            if (isset($_POST['usuario_tel']) && !empty($_POST['usuario_tel'])) {
                $tel = (int)filter_var($_POST['usuario_tel'], FILTER_SANITIZE_NUMBER_INT);
                if (strlen((string)$tel) == 8) {
                    $campos_actualizar[] = "usuario_tel = $tel";
                } else {
                    $errores[] = 'El teléfono debe tener exactamente 8 dígitos';
                    $validaciones_ok = false;
                }
            }

            if (isset($_POST['usuario_direc']) && !empty($_POST['usuario_direc'])) {
                $direc = ucwords(strtolower(trim(htmlspecialchars($_POST['usuario_direc']))));
                if (strlen($direc) >= 5) {
                    $campos_actualizar[] = "usuario_direc = '$direc'";
                } else {
                    $errores[] = 'La dirección debe ser más específica';
                    $validaciones_ok = false;
                }
            }

            if (isset($_POST['usuario_correo']) && !empty($_POST['usuario_correo'])) {
                $correo = strtolower(trim(filter_var($_POST['usuario_correo'], FILTER_SANITIZE_EMAIL)));
                if (filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                    // Verificar duplicados
                    $existeCorreo = self::fetchFirst("SELECT usuario_id FROM jemg_usuarios WHERE usuario_correo = '$correo' AND usuario_id != $id");
                    if (!$existeCorreo) {
                        $campos_actualizar[] = "usuario_correo = '$correo'";
                    } else {
                        $errores[] = 'El correo ya está registrado por otro usuario';
                        $validaciones_ok = false;
                    }
                } else {
                    $errores[] = 'Formato de correo electrónico inválido';
                    $validaciones_ok = false;
                }
            }

            if (!$validaciones_ok) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Errores de validación encontrados',
                    'errores' => $errores
                ]);
                return;
            }

            if (empty($campos_actualizar)) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'No se enviaron campos válidos para actualizar'
                ]);
                return;
            }

            // Ejecutar actualización parcial
            $set_clause = implode(', ', $campos_actualizar);
            $sql = "UPDATE jemg_usuarios SET $set_clause WHERE usuario_id = $id AND usuario_situacion = 1";
            
            $resultado = self::ejecutar($sql);

            if ($resultado) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Usuario actualizado parcialmente con éxito',
                    'campos_actualizados' => count($campos_actualizar),
                    'usuario_id' => $id
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error al actualizar el usuario'
                ]);
            }

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error en la actualización parcial',
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