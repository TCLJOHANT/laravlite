<?php 
namespace Lib;
class Route{
    public static $routes = [];
    public static function get($uri,$callback){
        $uri = trim($uri,'/');
        self::$routes['GET'][$uri] = $callback;
    }
    public static function post($uri,$callback){
        $uri = trim($uri,'/');
        self::$routes['POST'][$uri] = $callback;
    }

    public static function dispatch(){
        $uri = $_SERVER['REQUEST_URI'];
        $uri = trim($uri,'/');
        $method = $_SERVER['REQUEST_METHOD'];
    }
}