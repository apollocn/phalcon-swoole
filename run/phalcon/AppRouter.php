<?php
namespace run\phalcon;
use \Phalcon\Mvc\Router;
use run\phalcon\routers;
class AppRouter{
    private static $router;
    public static function getRouters(){
	self::$router = new Router(false);
	self::$router->mount(new routers\newsRouters);
	self::$router->mount(new routers\blogRoutes);
    //404
    self::$router->notFound(
        array(
            "controller" => "handler",
            "action"     => "notFound",
        )
    );
    //default
    self::$router->add("/", array(
        'controller' => 'index',
        'action' => 'index'
    ));
	return self::$router;
    }
}


?>