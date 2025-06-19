<?php

namespace Controllers;

use Exception;
use Model\ActiveRecord;
use MVC\Router;

class AppController
{
    public static function index(Router $router)
    {
       
        $router->render('login/index', [], 'layouts/login');
    }


    public static function login()
    {
        getHeadersApi();

        try {

            $usario = filter_var($_POST['usuario_dpi'], FILTER_SANITIZE_NUMBER_INT);
            $constrasena = htmlspecialchars($_POST['usuario_contra']);

            $queyExisteUser = "SELECT usuario_id, usuario_nom1, usuario_contra FROM jemg_usuario WHERE usuario_dpi = $usario AND usuario_situacion = 1";

            $ExisteUsuario = ActiveRecord::fetchArray($queyExisteUser)[0];

            if ($ExisteUsuario) {

                $passDB = $ExisteUsuario['usuario_contra'];

                if (password_verify($constrasena, $passDB)) {

                    session_start();

                    $nombreUser = $ExisteUsuario['usuario_nom1'];
                    $idUsuario = $ExisteUsuario['usuario_id'];

                    $_SESSION['nombre'] = $nombreUser;
                    $_SESSION['dpi'] = $usario;

                    $sqlpermisos = "SELECT permiso_clave as permiso from jemg_asig_permisos inner join jemg_permisos on asignacion_permiso_id = permiso_id where asignacion_usuario_id = $idUsuario";

                    $permisos = ActiveRecord::fetchArray($sqlpermisos);

                    foreach ($permisos as $key => $value) {
                       $_SESSION[$value['permiso']] = 1; 
                    }
                   

                    echo json_encode([
                        'codigo' => 1,
                        'mensaje' => 'usuario logueado existosamente',

                    ]);
                } else {
                    echo json_encode([
                        'codigo' => 0,
                        'mensaje' => 'La contraseña que ingreso es Incorrecta',

                    ]);
                }
            } else {
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El usuario que intenta loguearse NO EXISTE',

                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al intentar loguearse',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function logout()
    {

        isAuth();
        $_SESSION = [];
        $login = $_ENV['APP_NAME'];
        header("Location: /$login");
    }

    public static function renderInicio(Router $router){
        hasPermission(['ADMIN']);
        
        $router->render('pages/index', [], 'layouts/menu');
    }
}
