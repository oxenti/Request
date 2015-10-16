<?php
use Cake\Routing\Router;

Router::plugin('Request', function ($routes) {
    $routes->fallbacks('DashedRoute');
    $routes->resources('Requests', function ($routes) {
        $routes->resources('Requesthistorics');
    });
});
