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

        // Sanitización de primer nombre
        $_POST['usuario_nom1'] = ucwords(strtolower(trim(htmlspecialchars($_POST['usuario_nom1']))));
        
        $cantidad_nombre = strlen($_POST['usuario_nom1']);
        
        if ($cantidad_nombre < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nombre debe tener más de 1 carácter'
            ]);
            exit;
        }
        
        // Sanitización de primer apellido
        $_POST['usuario_ape1'] = ucwords(strtolower(trim(htmlspecialchars($_POST['usuario_ape1']))));
        $cantidad_apellido = strlen($_POST['usuario_ape1']);
        
        if ($cantidad_apellido < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El apellido debe tener más de 1 carácter'
            ]);
            exit;
        }
        
        // Validación de teléfono (INT en PostgreSQL)
        $_POST['usuario_tel'] = filter_var($_POST['usuario_tel'], FILTER_SANITIZE_NUMBER_INT);
        if (strlen($_POST['usuario_tel']) != 8 || !is_numeric($_POST['usuario_tel'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El teléfono debe tener 8 números'
            ]);
            exit;
        }
        
        // Sanitización de dirección
        $_POST['usuario_direc'] = ucwords(strtolower(trim(htmlspecialchars($_POST['usuario_direc']))));
        
        // Validación de DPI (VARCHAR en PostgreSQL)
        $_POST['usuario_dpi'] = trim(htmlspecialchars($_POST['usuario_dpi']));
        if (strlen($_POST['usuario_dpi']) != 13 || !is_numeric($_POST['usuario_dpi'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El DPI debe tener exactamente 13 dígitos'
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
        
        // Generar token único
        $_POST['usuario_token'] = uniqid();
        $dpi = $_POST['usuario_dpi'];
        
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
                'mensaje' => 'Solo se pueden cargar archivos JPG, PNG o JPEG',
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
                $usuario = new Usuarios($_POST);
                $usuario->usuario_fotografia = $ruta;
                $resultado = $usuario->crear();

                if($resultado['resultado'] == 1){
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
                        'datos' => $_POST,
                        'usuario' => $usuario,
                    ]);
                    exit;
                }
            } 
        } else {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error en la carga de fotografía',
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

    public static function modificarUsuario()
    {
        getHeadersApi();

        $id = $_POST['usuario_id'];

        // Sanitización de primer nombre
        $_POST['usuario_nom1'] = ucwords(strtolower(trim(htmlspecialchars($_POST['usuario_nom1']))));
        if (strlen($_POST['usuario_nom1']) < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nombre debe tener más de 1 carácter'
            ]);
            return;
        }

        // Sanitización de primer apellido
        $_POST['usuario_ape1'] = ucwords(strtolower(trim(htmlspecialchars($_POST['usuario_ape1']))));
        if (strlen($_POST['usuario_ape1']) < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El apellido debe tener más de 1 carácter'
            ]);
            return;
        }

        // Validación de teléfono (INT en PostgreSQL)
        $_POST['usuario_tel'] = filter_var($_POST['usuario_tel'], FILTER_SANITIZE_NUMBER_INT);
        if (strlen($_POST['usuario_tel']) != 8 || !is_numeric($_POST['usuario_tel'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El teléfono debe tener 8 números'
            ]);
            return;
        }

        // Sanitización de dirección
        $_POST['usuario_direc'] = ucwords(strtolower(trim(htmlspecialchars($_POST['usuario_direc']))));

        // Validación de DPI (VARCHAR en PostgreSQL)
        $_POST['usuario_dpi'] = trim(htmlspecialchars($_POST['usuario_dpi']));
        if (strlen($_POST['usuario_dpi']) != 13 || !is_numeric($_POST['usuario_dpi'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El DPI debe tener exactamente 13 dígitos'
            ]);
            return;
        }

        // Verificar si el DPI ya existe (excluyendo el usuario actual)
        $dpiExistente = self::fetchFirst("SELECT usuario_id FROM jemg_usuarios WHERE usuario_dpi = '{$_POST['usuario_dpi']}' AND usuario_id != $id");
        if ($dpiExistente) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El DPI ya está registrado por otro usuario'
            ]);
            return;
        }

        try {
            $data = Usuarios::find($id);
            $data->sincronizar([
                'usuario_nom1' => $_POST['usuario_nom1'],
                'usuario_ape1' => $_POST['usuario_ape1'],
                'usuario_tel' => intval($_POST['usuario_tel']), // Convertir a entero para PostgreSQL
                'usuario_direc' => $_POST['usuario_direc'],
                'usuario_dpi' => $_POST['usuario_dpi'],
                'usuario_situacion' => 1
            ]);
            
            $data->actualizar();
            
            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'La información del usuario ha sido modificada exitosamente'
            ]);
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al modificar usuario',
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