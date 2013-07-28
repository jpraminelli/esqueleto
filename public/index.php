<?php
function pe($texto){
    echo '<hr>';
    echo '<pre>';
    print_r($texto);
    echo '</pre>';
    die('<hr>');
}
/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir(dirname(__DIR__));

// Setup autoloading
include 'init_autoloader.php';

// Run the application!
Zend\Mvc\Application::init(include 'config/application.config.php')->run();
