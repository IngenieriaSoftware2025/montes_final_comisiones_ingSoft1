<?php

namespace Controllers;

use Exception;
use MVC\Router;
use Model\ActiveRecord;
use Model\Usuarios;

class UsuarioController extends ActiveRecord
{

    public static function renderizarPagina(Router $router)
    {
        $router->render('usuarios/index', []);
    }


    public static function guardarAPI()
    {
        getHeadersApi();
    
        //saniticacion de nombre
        $_POST['usuario_nom1'] = ucwords(strtolower(trim(htmlspecialchars($_POST['usuario_nom1']))));
        
        $cantidad_nombre = strlen($_POST['usuario_nom1']);
        
        if ($cantidad_nombre < 2) {
            
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Primer nombre debe de tener mas de 1 caracteres'
            ]);
            exit;
        }
        
        // Segundo nombre es opcional
        if (!empty($_POST['usuario_nom2'])) {
            $_POST['usuario_nom2'] = ucwords(strtolower(trim(htmlspecialchars($_POST['usuario_nom2']))));
            
            $cantidad_nombre2 = strlen($_POST['usuario_nom2']);
            
            if ($cantidad_nombre2 < 2) {
              
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Segundo nombre debe de tener mas de 1 caracteres'
                ]);
                exit;
            }
        } else {
            $_POST['usuario_nom2'] = null;
        }
        
        $_POST['usuario_ape1'] = ucwords(strtolower(trim(htmlspecialchars($_POST['usuario_ape1']))));
        $cantidad_apellido = strlen($_POST['usuario_ape1']);
        
        if ($cantidad_apellido < 2) {
         
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Primer apellido debe de tener mas de 1 caracteres'
            ]);
            exit;
        }
        
        // Segundo apellido es opcional
        if (!empty($_POST['usuario_ape2'])) {
            $_POST['usuario_ape2'] = ucwords(strtolower(trim(htmlspecialchars($_POST['usuario_ape2']))));
            $cantidad_apellido2 = strlen($_POST['usuario_ape2']);
            
            if ($cantidad_apellido2 < 2) {
                
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Segundo apellido debe de tener mas de 1 caracteres'
                ]);
                exit;
            }
        } else {
            $_POST['usuario_ape2'] = null;
        }
        
        $_POST['usuario_tel'] = filter_var($_POST['usuario_tel'], FILTER_SANITIZE_NUMBER_INT);
        if (strlen($_POST['usuario_tel']) < 8 || strlen($_POST['usuario_tel']) > 9) {
        
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El telefono debe de tener entre 8 y 9 numeros'
            ]);
            exit;
        }
        
        $_POST['usuario_direc'] = trim(htmlspecialchars($_POST['usuario_direc']));
        if (strlen($_POST['usuario_direc']) < 10) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La direccion debe de tener al menos 10 caracteres'
            ]);
            exit;
        }
        
        $_POST['usuario_dpi'] = filter_var($_POST['usuario_dpi'], FILTER_VALIDATE_INT);
        if (strlen($_POST['usuario_dpi']) != 13) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La cantidad de digitos del DPI debe de ser igual a 13'
            ]);
            exit;
        }
        
        $_POST['usuario_correo'] = filter_var($_POST['usuario_correo'], FILTER_SANITIZE_EMAIL);
        
        if (!filter_var($_POST['usuario_correo'], FILTER_VALIDATE_EMAIL)){
          
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El correo electronico no es valido'
            ]);
            exit;
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
        
        // VALIDACIÓN: Contraseña
        if (strlen($_POST['usuario_contra']) < 8) {
      
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La contraseña debe tener al menos 8 caracteres'
            ]);
            exit;
        }
        
        // VALIDACIÓN: Confirmar contraseña
        if ($_POST['usuario_contra'] !== $_POST['confirmar_contra']) {
            
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Las contraseñas no coinciden'
            ]);
            exit;
        }
        
        $_POST['usuario_token'] = uniqid();
        $dpi = $_POST['usuario_dpi'];
        $_POST['usuario_fecha_creacion'] = '';
        $_POST['usuario_fecha_contra'] = '';
        
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
                'mensaje' => 'Solo puede cargar archivos JPG, PNG o JPEG',
            ]);
            exit;
        }
        
        if ($fileSize >= 2000000) {
            
            http_response_code(400);
            echo json_encode([
                'codigo' => 2,
                'mensaje' => 'La imgagen debe pesar menos de 2MB',
            ]);
            exit;
        }
        
        if ($fileError === 0) {
                $ruta = "storage/fotosUsuarios/$dpi.$fileExtension";
                $subido = move_uploaded_file($file['tmp_name'], __DIR__ . "../../" . $ruta);
                
                if ($subido) {
                    
                    $_POST['usuario_contra'] = password_hash($_POST['usuario_contra'], PASSWORD_DEFAULT);
                    $foto = base64_encode(file_get_contents(__DIR__ . '/../' . $ruta));
                    $_SESSION['user']->foto = $foto;
                    $usuario = new Usuarios($_POST);
                    $usuario->usuario_fotografia = $ruta;
                    $resultado = $usuario->crear();

                    if($resultado['resultado'] ==1){
                        
                        http_response_code(200);
                        echo json_encode([
                            'codigo' => 1,
                            'mensaje' => 'Usuario registrado correctamente',
                        ]);
                        
                        exit;
                    }else{
                        
                http_response_code(500);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error en registrar al usuario',
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
                    'mensaje' => 'Error en la carga de fotografia',
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
                'mensaje' => 'Primer nombre debe tener más de 1 caracter'
            ]);
            return;
        }

        // Sanitización de segundo nombre (opcional)
        if (!empty($_POST['usuario_nom2'])) {
            $_POST['usuario_nom2'] = ucwords(strtolower(trim(htmlspecialchars($_POST['usuario_nom2']))));
            if (strlen($_POST['usuario_nom2']) < 2) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Segundo nombre debe tener más de 1 caracter'
                ]);
                return;
            }
        } else {
            $_POST['usuario_nom2'] = null;
        }

        // Sanitización de primer apellido
        $_POST['usuario_ape1'] = ucwords(strtolower(trim(htmlspecialchars($_POST['usuario_ape1']))));
        if (strlen($_POST['usuario_ape1']) < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Primer apellido debe tener más de 1 caracter'
            ]);
            return;
        }

        // Sanitización de segundo apellido (opcional)
        if (!empty($_POST['usuario_ape2'])) {
            $_POST['usuario_ape2'] = ucwords(strtolower(trim(htmlspecialchars($_POST['usuario_ape2']))));
            if (strlen($_POST['usuario_ape2']) < 2) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Segundo apellido debe tener más de 1 caracter'
                ]);
                return;
            }
        } else {
            $_POST['usuario_ape2'] = null;
        }

        // Validación de teléfono
        $_POST['usuario_tel'] = filter_var($_POST['usuario_tel'], FILTER_SANITIZE_NUMBER_INT);
        if (strlen($_POST['usuario_tel']) < 8 || strlen($_POST['usuario_tel']) > 9) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El teléfono debe tener entre 8 y 9 números'
            ]);
            return;
        }

        // Sanitización de dirección
        $_POST['usuario_direc'] = trim(htmlspecialchars($_POST['usuario_direc']));

        // Validación de DPI
        $_POST['usuario_dpi'] = filter_var($_POST['usuario_dpi'], FILTER_SANITIZE_NUMBER_INT);
        if (strlen($_POST['usuario_dpi']) != 13) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El DPI debe tener exactamente 13 dígitos'
            ]);
            return;
        }

        // Validación de correo
        $_POST['usuario_correo'] = filter_var($_POST['usuario_correo'], FILTER_SANITIZE_EMAIL);
        if (!filter_var($_POST['usuario_correo'], FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El correo electrónico no es válido'
            ]);
            return;
        }

        // Verificar duplicados 
        $usuarioExistente = self::fetchFirst("SELECT usuario_id FROM jemg_usuarios WHERE usuario_correo = '{$_POST['usuario_correo']}' AND usuario_id != $id");
        if ($usuarioExistente) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El correo electrónico ya está registrado por otro usuario'
            ]);
            return;
        }

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
            
            $datosActualizar = [
                'usuario_nom1' => $_POST['usuario_nom1'],
                'usuario_nom2' => $_POST['usuario_nom2'],
                'usuario_ape1' => $_POST['usuario_ape1'],
                'usuario_ape2' => $_POST['usuario_ape2'],
                'usuario_tel' => $_POST['usuario_tel'],
                'usuario_direc' => $_POST['usuario_direc'],
                'usuario_dpi' => $_POST['usuario_dpi'],
                'usuario_correo' => $_POST['usuario_correo'],
                'usuario_situacion' => 1
            ];

            if (!empty($_POST['usuario_contra'])) {
                if (strlen($_POST['usuario_contra']) < 8) {
                    http_response_code(400);
                    echo json_encode([
                        'codigo' => 0,
                        'mensaje' => 'La contraseña debe tener al menos 8 caracteres'
                    ]);
                    return;
                }

                if ($_POST['usuario_contra'] !== $_POST['confirmar_contra']) {
                    http_response_code(400);
                    echo json_encode([
                        'codigo' => 0,
                        'mensaje' => 'Las contraseñas no coinciden'
                    ]);
                    return;
                }
                
                $datosActualizar['usuario_contra'] = password_hash($_POST['usuario_contra'], PASSWORD_DEFAULT);
            }
            
            $data->sincronizar($datosActualizar);
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