<?php

namespace Controllers;

use Exception;
use MVC\Router;
use Model\ActiveRecord;
use Model\Permisos;

class PermisoController extends ActiveRecord
{

    public static function renderizarPagina(Router $router)
    {
        $router->render('permisos/index', []);
    }

}