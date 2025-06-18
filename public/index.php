<?php 
require_once __DIR__ . '/../includes/app.php';


use MVC\Router;
use Controllers\AppController;
use Controllers\RegistroController;
$router = new Router();
$router->setBaseURL('/' . $_ENV['APP_NAME']);

$router->get('/', [AppController::class,'index']);

//RUTAS PARA LOGIN/OUT
$router->get('/', [AppController::class,'index']);
$router->get('/logout', [AppController::class,'logout']);
$router->post('/API/login', [AppController::class,'login']);
$router->get('/API/logout', [AppController::class,'logout']);
$router->get('/inicio', [AppController::class,'renderInicio']);

//RUTAS PARA USUARIOS
$router->get('/usuarios', [RegistroController::class,'renderizarPagina']);
$router->post('/usuarios/guardar', [RegistroController::class,'guardarAPI']);
$router->get('/usuarios/buscar', [RegistroController::class,'buscarUsuarios']);
$router->post('/usuarios/modificar', [RegistroController::class,'modificarUsuario']);
$router->post('/usuarios/eliminar', [RegistroController::class,'eliminarUsuario']);

//RUTAS PARA APLICACIONES
$router->get('/aplicaciones', [RegistroController::class,'renderizarPagina']);
$router->post('/aplicaciones/guardar', [RegistroController::class,'guardarAPI']);
$router->get('/aplicaciones/buscar', [RegistroController::class,'buscaraplicaciones']);
$router->post('/aplicaciones/modificar', [RegistroController::class,'modificarAplicacion']);
$router->post('/aplicaciones/eliminar', [RegistroController::class,'eliminarAplicacion']);

//RUTAS PARA PERMISOS
$router->get('/permisos', [RegistroController::class,'renderizarPagina']);
$router->post('/permisos/guardar', [RegistroController::class,'guardarAPI']);
$router->get('/permisos/buscar', [RegistroController::class,'buscarpermisos']);
$router->post('/permisos/modificar', [RegistroController::class,'modificarPermiso']);
$router->post('/permisos/eliminar', [RegistroController::class,'eliminarPermiso']);

// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();
