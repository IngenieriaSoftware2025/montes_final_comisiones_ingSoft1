<?php 
require_once __DIR__ . '/../includes/app.php';


use MVC\Router;
use Controllers\AppController;
use Controllers\UsuarioController;
$router = new Router();
$router->setBaseURL('/' . $_ENV['APP_NAME']);

$router->get('/', [AppController::class,'index']);

//RUTAS PARA USUARIOS
$router->get('/usuarios', [UsuarioController::class,'renderizarPagina']);
$router->post('/usuarios/guardar', [UsuarioController::class,'guardarAPI']);
$router->get('/usuarios/buscar', [UsuarioController::class,'buscarUsuarios']);
$router->post('/usuarios/modificar', [UsuarioController::class,'modificarUsuario']);
$router->post('/usuarios/eliminar', [UsuarioController::class,'eliminarUsuario']);

// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();
