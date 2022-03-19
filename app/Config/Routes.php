<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
// $routes->get('/', 'Home::index');

$routes->post('/auth/login', 'Auth::login');

// Grupo de rutas para los clientes
$routes->group('api', ['namespace' => 'App\Controllers\API', 'filter' => 'authFilter'], function($routes) {
    $routes->get('clientes', 'ClientesController::index');
    $routes->get('clientes/{id}', 'ClientesController::show');
    $routes->post('clientes', 'ClientesController::create');
    $routes->put('clientes/{id}', 'ClientesController::update');
    $routes->delete('clientes/{id}', 'ClientesController::delete');


// Grupo de rutas para las cuentas

    $routes->get('cuentas', 'CuentasController::index');
    $routes->get('cuentas/{id}', 'CuentasController::show');
    $routes->post('cuentas', 'CuentasController::create');
    $routes->put('cuentas/{id}', 'CuentasController::update');
    $routes->delete('cuentas/{id}', 'CuentasController::delete');


// Grupo de rutas para las transacciones

    $routes->get('transacciones', 'TransaccionesController::index');
    $routes->get('transacciones/{id}', 'TransaccionesController::show');
    $routes->post('transacciones', 'TransaccionesController::create');
    $routes->put('transacciones/{id}', 'TransaccionesController::update');
    $routes->put('transacciones/cliente/{id}', 'TransaccionesController::getTrasaccionesByCliente');
    $routes->delete('transacciones/{id}', 'TransaccionesController::delete');


// Grupo de rutas para el tipo de transaccion

    $routes->get('tipotransacciones', 'TipoTransaccionesController::index');
    $routes->get('tipotransacciones/{id}', 'TipoTransaccionesController::show');
    $routes->post('tipotransacciones', 'TipoTransaccionesController::create');
    $routes->put('tipotransacciones/{id}', 'TipoTransaccionesController::update');
    $routes->delete('tipotransacciones/{id}', 'TipoTransaccionesController::delete');

// Grupo para las rutas de usuarios

    $routes->get('', 'UsuariosController::index');
    $routes->get('{id}', 'UsuariosController::show');
    $routes->post('', 'UsuariosController::create');
    $routes->put('{id}', 'UsuariosController::update');
    $routes->delete('{id}', 'UsuariosController::delete');

// Grupo para las rutas de roles

    $routes->get('roles', 'RolesController::index');
    $routes->get('roles/{id}', 'RolesController::show');
    $routes->post('roles', 'RolesController::create');
    $routes->put('roles/{id}', 'RolesController::update');
    $routes->delete('roles/{id}', 'RolesController::delete');

});
/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
