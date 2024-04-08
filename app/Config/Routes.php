<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/post', 'Post::index');
$routes->get('/post/create', 'Post::create');
$routes->post('/post/store', 'Post::store');
// $routes->get('/post/edit/(::any)', 'Post::edit');
$routes->add('post/edit/(:segment)', 'Post::edit/$1');
$routes->add('post/update/(:segment)', 'Post::update/$1');
$routes->add('post/delete/(:segment)', 'Post::delete/$1');

//...
$routes->get('notification', 'MessageController::showSweetAlertMessages');
//...


