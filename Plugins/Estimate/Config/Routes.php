<?php

namespace Config;

$routes = Services::routes();

$routes->get('estimate_task', 'Estimate_task::index', ['namespace' => 'Estimate\Controllers']);
$routes->get('estimate_task/(:any)', 'Estimate_task::$1', ['namespace' => 'Estimate\Controllers']);
$routes->post('estimate_task/(:any)', 'Estimate_task::$1', ['namespace' => 'Estimate\Controllers']);
