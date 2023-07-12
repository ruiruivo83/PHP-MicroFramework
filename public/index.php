<?php

/**
 * FRONT CONTROLLER
 *
 * PHP version 7.4
 */

// echo 'Requested URL = "' . $_SERVER['QUERY_STRING'] . '"';

/**
 * Routing
 */
require '../Core/Router.php';

$router = new Router();

//echo get_class($router);

// Add the routes
$router->add('', ['controller' => 'Home', 'action' => 'index']);
$router->add('posts', ['controller' => 'Posts', 'action' => 'index']);
// $router->add('posts/new', ['controller' => 'Posts', 'action' => 'new']);
$router->add('{controller}/{action}');
$router->add('{controller}/{id:\d+}/{action}');
$router->add('admin/{action}/{controller}');
    
// Display the routing table

echo '<pre>';
var_dump($router->getRoutes());
echo '</pre>';


// URL - Match the requested route
$url = $_SERVER['QUERY_STRING'];

if ($router->match($url)) {
    echo '<pre>';
    var_dump($router->getParams());
    echo '<pre>';
} else {
    echo "No route found for URL: '$url'";
}
