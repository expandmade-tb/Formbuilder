<?php

use controller\FormDemo;
use lib\Autoloader;

define('BASEPATH', realpath(__DIR__.'/../'));
define('APP', BASEPATH.'/app');
define('JAVASCRIPT', '/js');
define('STYLESHEET', '/css');
define('IMAGES', '/img');

chdir(__DIR__);
require_once APP.'/lib/Autoloader.php';
Autoloader::instance();
$demo = new FormDemo();
$demo->index();
$uri = $_SERVER["REQUEST_URI"]??'/contact';

if ( $uri == '/mixed' )
    $demo->MixedForm();
else
    $demo->ContactForm();