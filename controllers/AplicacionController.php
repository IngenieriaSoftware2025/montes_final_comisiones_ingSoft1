<?php

namespace Controllers;

use Exception;
use MVC\Router;
use Model\ActiveRecord;
use Model\Aplicaciones;

class RegistroController extends ActiveRecord
{

    public static function renderizarPagina(Router $router)
    {
        $router->render('aplicaciones/index', []);
    }

}