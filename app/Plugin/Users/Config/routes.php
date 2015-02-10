<?php
$filename = APP . 'Config' . DS . 'Users' . DS . 'routes.php';
if(file_exists($filename)) {
    include($filename);
}

Router::connect('/users', array('plugin' => 'users', 'controller' => 'users'));
Router::connect('/users/index/*', array('plugin' => 'users', 'controller' => 'users'));
Router::connect('/users/:action/*', array('plugin' => 'users', 'controller' => 'users'));
Router::connect('/users/users/:action/*', array('plugin' => 'users', 'controller' => 'users'));
Router::connect('/login', array('plugin' => 'users', 'controller' => 'users', 'action' => 'login'));
Router::connect('/logout', array('plugin' => 'users', 'controller' => 'users', 'action' => 'logout'));
Router::connect('/register', array('plugin' => 'users', 'controller' => 'users', 'action' => 'register'));