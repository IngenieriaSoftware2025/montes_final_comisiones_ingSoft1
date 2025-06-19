<?php 
require_once __DIR__ . '/../includes/app.php';

use MVC\Router;
use Controllers\AppController;
use Controllers\UsuariosController;
use Controllers\AplicacionController;
use Controllers\PermisoController;

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
$router->get('/usuarios', [UsuariosController::class,'renderizarPagina']);
$router->post('/usuarios/guardar', [UsuariosController::class,'guardarAPI']);
$router->get('/usuarios/buscar', [UsuariosController::class,'buscarUsuarios']);
$router->post('/usuarios/modificar', [UsuariosController::class,'modificarUsuario']);
$router->post('/usuarios/eliminar', [UsuariosController::class,'eliminarUsuario']);

//RUTAS PARA APLICACIONES
$router->get('/aplicaciones', [AplicacionController::class,'renderizarPagina']);
$router->post('/aplicaciones/guardar', [AplicacionController::class,'guardarAPI']);
$router->get('/aplicaciones/buscar', [AplicacionController::class,'buscarAplicaciones']);
$router->post('/aplicaciones/modificar', [AplicacionController::class,'modificarAplicacion']);
$router->post('/aplicaciones/eliminar', [AplicacionController::class,'eliminarAplicacion']);

//RUTAS PARA PERMISOS
$router->get('/permisos', [PermisoController::class,'renderizarPagina']);
$router->post('/permisos/guardar', [PermisoController::class,'guardarAPI']);
$router->get('/permisos/buscar', [PermisoController::class,'buscarPermisos']);
$router->post('/permisos/modificar', [PermisoController::class,'modificarPermiso']);
$router->post('/permisos/eliminar', [PermisoController::class,'eliminarPermiso']);

// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();