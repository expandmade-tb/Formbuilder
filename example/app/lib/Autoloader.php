<?php

namespace lib;

 class Autoloader {
    private static ?Autoloader $instance = null;
    private array $namespaces = [];
    private string $app;

    function __construct(string $app_location='') {    
        defined('BASEPATH') || exit('BASEPATH not defined');

        if ( empty($app_location) )
            $this->app = BASEPATH.'/app';
        else
            $this->app = $app_location;

        $this->add_namespace('controller');
        spl_autoload_register(array($this,'autoload'));
    }

    public static function instance(string $app_location='') : Autoloader {
        if (self::$instance == null)
            self::$instance = new Autoloader($app_location);
   
        return self::$instance;
    }

    public function app() : string {
        return $this->app;
    }

    public function add_namespace(string $namespaces) : void {
        $this->namespaces[] = $namespaces;
    }

    public function autoload ( string $className ) : void {
        $components = explode('\\', $className);
        $namespace = $components['0'];

        if ( in_array($namespace, $this->namespaces ) )
            $file =  $this->app.'/'.str_replace('\\', DIRECTORY_SEPARATOR, $className).'.php';

        else
            $file = $this->app.'/lib/'.str_replace('\\', DIRECTORY_SEPARATOR, $className).'.php';

        if (file_exists($file))
            require $file;
        }
}