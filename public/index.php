<?php

/**
 * FRONT CONTROLLER
 *
 * PHP version 7.4
 */

/**
 * Composer - Autoload
 */
require '../vendor/autoload.php';

/** 
 * Error and Exception handling
 */
error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');


/**
 * Session
 */
session_start();

/**
 * Routing
 */
$router = new Core\Router();

// echo get_class($router);

// Add the routes
$router->add('', ['controller' => 'Home', 'action' => 'index']);
$router->add('login', ['controller' => 'Login', 'action' => 'new']);
$router->add('logout', ['controller' => 'Login', 'action' => 'destroy']);
// $router->add('{controller}');
$router->add('{controller}/{action}');
// $router->add('{controller}/{id:\d+}/{action}');
// $router->add('admin/{controller}/{action}', ['namespace' => 'Admin']);

// Display the routing table
/*
echo '<pre>';
var_dump($router->getRoutes());
echo '</pre>';
*/

$router->dispatch($_SERVER['QUERY_STRING']);

// For debug
// phpinfo();
// phpinfo(INFO_MODULES);
if (\App\Config::SHOW_ERRORS) {
    var_dump(get_defined_vars());
}